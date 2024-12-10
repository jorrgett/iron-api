<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Requests\Auth\{
    ValidCredential,
    CheckCredentials,
    LoginRequest,
    RegisterRequest,
    ResetPasswordRequest,
    UpdatePasswordRequest,
    CodeForRegisterRequest,
    ValidateCodeForRegisterRequest
};
use App\Helpers\VerifyApp;
use Spatie\Permission\Models\Role;
use App\Http\Resources\User\UserAuthResource;
use App\Repositories\User\UserRepository;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users.
    |
    */

    protected $userRepository, $user;

    /**
     * Create a new authentication controller instance.
     *
     * @param UserRepository $userRepository
     */

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials() == false ? [] : $request->getCredentials();

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['errors' => 'The credentials are invalid'], 401);
        }

        $check_app = new VerifyApp();

        if ($check_app->checkStatusApp($request->version, $request->platform)) {
            (new VerifyApp())->syncNewSession($request->all());
            return $this->createNewToken($token);
        }

        return response()->json(['errors' => 'Your version is out of date, please update before synchronizing'], 400);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function register(RegisterRequest $request)
    {

        $user = $this->userRepository->create($request->all());
        $client = Role::findByName('Client');
        $user->assignRole($client);

        return $this->createNewToken(auth()->login($user));
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth()->user();

        if ($user) {
            $user->fcm_token = null;
            $user->save();
        }

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user' => new UserAuthResource(auth()->user())
        ]);
    }

    /**
     * Sent code for register
     *
     * @param  array  $data
     * @return User
     */
    public function sendCodeForRegister(CodeForRegisterRequest $request)
    {
        return $this->userRepository->codeForRegister($request->validated());
    }


    /**
     * Validate code for register
     *
     * @param  string $code
     * @param  request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateCodeForRegister(ValidateCodeForRegisterRequest $request)
    {

        $user = $this->userRepository->validateCode($request->validated());

        return $user;
    }


    /**
     * Forget Password
     *
     * @param  array  $data
     * @return User
     */
    public function forget(UpdatePasswordRequest $request)
    {
        return $this->userRepository->recoveryPassword($request->validated());
    }


    /**
     * Update Password
     *
     * @param  string $code
     * @param  request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUpdatePassword(ResetPasswordRequest $request)
    {
        try {
            $user = $this->userRepository->setNewPassword($request->validated());

            return $this->createNewToken(auth()->login($user));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Generate verification credentials
     *
     * @param  CheckCredentials $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCredentials(CheckCredentials $request)
    {
        return $this->userRepository->checkPhoneOrEmail($request->type, $request->all());
    }

    /**
     * Confirm the code received according to type
     *
     * @param  ValidCredential $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmCredential(ValidCredential $request)
    {
        return $this->userRepository->confirmPhoneOrEmail($request->all());
    }
}
