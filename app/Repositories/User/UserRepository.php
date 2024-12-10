<?php

namespace App\Repositories\User;

use App\Helpers\FirebaseMessaging;
use App\Models\User;
use App\Mail\SendVerifyEmail;
use App\Mail\RecoveryPassword;
use App\Helpers\MensajeroHelper;
use App\Helpers\ParametersHelper;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Repositories\BaseInterface;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\User\UserCollection;
use App\Models\Contacts;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRepository implements BaseInterface
{

    protected $model;
    protected $user;
    protected $params;
    protected $messages;

    /**
     * User Repository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
        $this->params = new ParametersHelper();
        $this->messages = new FirebaseMessaging;
    }

    /**
     * Get all paginated users
     *
     * @param $data
     *
     * @return UserCollection
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $filter = $data['search'] ?? null;

        if ($filter) {
            return $this->model->Orwhere('full_name', 'ilike', "%{$filter}%")
                ->OrWhere('email', 'ilike', "%{$filter}%")
                ->OrWhere('res_partner_id', 'like', "%{$filter}%")
                ->paginate($page);
        } else {

            return $this->model::paginate($page);
        }
    }

    /**
     * Store a newly created user in storage
     *
     * @param $data
     *
     * @return UserCollection
     */
    public function create(array $data): User
    {
        if (User::where('phone', ltrim($data['phone'], '0'))->exists()) {
            throw new HttpResponseException(response()->json([
                'error' => 'El número de teléfono ya está registrado.'
            ], 400));
        }
    
        $user = new User();
    
        if (isset($data['phone'])) {
            $data['phone'] = ltrim($data['phone'], '0');
        }
    
        $user->fill($data);
        $user->password = bcrypt($data['password']);
        $user->save();
    
        return $user;
    }
    

    /**
     * Display the specified user by field.
     *
     * @param $data
     *
     * @return UserCollection
     */
    public function getByField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $operator, $value)->first();
    }

    /**
     * Remove the specified user in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
        return User::findOrFail($id)->delete();
    }

    /**
     * Update the specified user in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
        $user = $this->getByField('id', $id);

        if (!$user) {
            return response()->json(
                ['message' =>  "Whoops, we could not find a user with ID: $id"],
                400
            );
        }

        if ($this->checkPassword($id, $data)) {
            $user->fill($data);
            if (isset($data['new_password'])) {
                $user['password'] = bcrypt($data['new_password']);
            }
            $user->save();

            return $user;
        }

        return false;
    }

    public function UpdateByAdmin($id, array $data)
    {
        $user = $this->getByField('id', $id);

        if (!$user) {
            return response()->json(
                ['message' =>  "Whoops, we could not find a user with ID: $id"],
                400
            );
        }

        if ($data['role_id'] != 0) {
            $role = Role::findById($data['role_id']);
            $user->syncRoles($role);
        } else {
            foreach ($user->roles as $role) {
                $user->removeRole($role['name']);
            }
        }
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->fill($data);
        $user->save();

        return $user;
    }

    public function updateTermsAndConditions(int $id, array $data): User
    {
        $user = $this->model->findOrFail($id);

        $user->terms_and_conditions_id = $data['terms_and_conditions_id'] ?? $user->terms_and_conditions_id;
        $user->legal_disclaimer_id = $data['legal_disclaimer_id'] ?? $user->legal_disclaimer_id;
        $user->privacy_policy_id = $data['privacy_policy_id'] ?? $user->privacy_policy_id;
        $user->legals_accepted = $data['legals_accepted'] ?? $user->legals_accepted;
        $user->save();

        return $user;
    }

    /**
     * Assign FCM token to specific user
     */
    public function updateFcmToken(int $id, array $data)
    {
        $user = $this->model->findOrFail($id);
        $user->fcm_token = $data['fcm_token'];
        $user->save();

        return $user;
    }

    /**
     * Update the specified user in storage
     *
     * @param array $data
     */
    public function recoveryPassword($data)
    {
        if (isset($data['phone'])) {
            # Removes zero and concat country code
            $data['phone'] = ltrim($data['phone'], '0');

            $user = User::where('phone', $data['phone'])
                ->where('country_code', $data['country_code'])->first();

            $data['phone'] = $data['country_code'] . $data['phone'];

            $generate = $this->generateCode('reset-password', 'phone', 'phone', $data['phone'], $user);
            $message = 'Tu codigo para reestablecer tu contrasena es: ';

            (new MensajeroHelper())->sendCodePhone($data['phone'], $message, $generate->code);
            return response()->json(['message' => "An message with the password reset code has been sent"]);
        }

        if (isset($data['email'])) {
            $user = $this->getByField('email', $data['email']);
            $generate = $this->generateCode('reset-password', 'email', 'email', $data['email'], $user);

            Mail::to($data['email'])->send(new RecoveryPassword($user->full_name, $generate->code));
            return response()->json(['message' => "An email with the password reset code has been sent"]);
        }
    }


    /**
     * Generate code for manual register
     *
     * @param array $data
     */
    public function codeForRegister($data)
    {
        if (isset($data['phone']) && isset($data['country_code'])) {

            $countryCode = ltrim($data['country_code'], '+');
            $phoneNumber = ltrim($data['phone'], '0');

            $contact = Contacts::whereRaw("REGEXP_REPLACE(phone, '[^0-9]', '', 'g') = ?",[$phoneNumber])->first();

        } elseif (isset($data['vat'])) {
            $vat = preg_replace('/\D/', '', $data['vat']);
            $contact = Contacts::whereRaw("REGEXP_REPLACE(vat, '[^0-9]', '', 'g') = ?", [$vat])->first();
        }
    
        if (!$contact) {
            return response()->json(['message' => "El teléfono o VAT no fue encontrado en la tabla de contactos"], 404);
        }
    
        $contactPhone = str_replace([' ', '-'], '', $contact['phone']);
        $contactPhone = ltrim($contactPhone, '0');

        $countryCode = $contact->country_code;
    
        $user = User::where('country_code', $countryCode)
                    ->where('phone', $contactPhone)
                    ->first();
    
        if (!$user) {
            return response()->json(['message' => "Usuario no encontrado con ese teléfono"], 404);
        }

        $complete_number = $contact->country_code.$contactPhone;
    
        $lastTwoDigits = substr($contactPhone, -2);
        $generate = $this->generateCode('register', 'phone', 'phone', $contactPhone, $user);
    
        $message = 'Tu código para completar tu registro es: ';
        (new MensajeroHelper())->sendCodePhone($complete_number, $message, $generate->code);
    
        return response()->json([
            'message' => "Se ha enviado un mensaje con el código para completar el registro",
            'last_two_digits' => $lastTwoDigits
        ]);
    }

    private function formatPhoneNumber($countryCode, $phoneNumber)
    {
        $prefix = substr($phoneNumber, 0, 3);
        $mainNumber = substr($phoneNumber, 3);
    
        return $countryCode . ' ' . $prefix . '-' . $mainNumber;
    }
    
    
    /**
     * Set a new password in specified user in storage
     *
     * @param array $data
     */
    public function setNewPassword($data)
    {
        $user = $this->verifyToken($data['code']);

        if ($user) {
            return $this->postResetPassword($data, $user);
        }

        throw new \Exception('Your code has expired, please request a new password reset.');
    }

    public function validateCode($data)
    {
        $user = $this->verifyToken($data['code']);

        if ($user) {
            return $this->setResPartnerID($data, $user);
        }

        throw new \Exception('Your code has expired, please request a new password reset.');
    }


    /**
     * Generate a new code
     *
     * @param string $email
     */
    private function generateCode($route, $type, $field, $value, $user)
    {

        DB::table('user_access_tokens')->where($field, $value)->delete();

        DB::table('user_access_tokens')->Insert([
            'user_id' => $user['id'],
            'type' => $route,
            $type == 'email' ? $type : 'phone' => $value,
            'code' => rand(100000, 999999),
            'created_at' => now()
        ]);

        return DB::table('user_access_tokens')
            ->select('code')
            ->where($field, $value)
            ->first();
    }


    /**
     * Check if code is valid
     *
     * @param integer $code
     */
    private function verifyToken($code)
    {
        $query = DB::table('user_access_tokens')->where('code', $code);
        $resetToken = $query->first();
        $minutes = $this->params->get_app_parameters('reset_password_token_type');

        if (empty($resetToken)) {
            return null;
        }

        $createdAt = Carbon::parse($resetToken->created_at);

        if (now() < $createdAt->addMinutes($minutes)) {
            return $resetToken->user_id;
        } else {
            return false;
        }
    }


    /**
     * Clean user_access_tokens
     *
     * @param array $data
     * @param integer $user_id
     */
    private function postResetPassword($data, $user_id)
    {
        $refresh_user = $this->getByField('id', $user_id);
        DB::table('user_access_tokens')->where('user_id', $refresh_user->id)->delete();

        $data['password'] = bcrypt($data['password']);
        $refresh_user->fill($data);
        $refresh_user->save();

        return $this->getByField('id', $user_id);
    }


    private function setResPartnerID($data, $user_id)
    {
        $refresh_user = $this->getByField('id', $user_id);
        DB::table('user_access_tokens')->where('user_id', $refresh_user->id)->delete();

        $user = User::where('id', $user_id)->first();
        
        // $formattedPhone = $this->formatPhoneNumber($user['country_code'], $user['phone']);
        $contact = Contacts::where('phone', $user['phone'])->first();

        $user_data = ['res_partner_id' => $contact->odoo_id];
        $refresh_user->fill($user_data);
        $refresh_user->save();

        return $this->getByField('id', $user_id);
    }


    /**
     * check Password
     *
     * @param array $data
     * @param integer $user_id
     */
    private function checkPassword($id, $data)
    {
        $user = User::findOrFail($id);

        if (auth()->attempt([
            isset($user->email) ? 'email' : 'phone' => $user->email ?? $user->phone,
            'password' => $data['current_password']
        ])) {
            return true;
        }

        return false;
    }

    /**
     * Check if the code sent is to phone or email
     *
     */
    public function checkPhoneOrEmail($type, $data)
    {
        # Search the user with data validation
        $user = auth()->user();
        $generate = $this->generateCode('check-phone/email', $type, $type, $data[$type], $user);
        $message = "Su codigo para verificacion de tu teléfono es:";
        $phone = isset($data['phone']) ? $data['country_code'] . ltrim($data['phone'], '0') : null;

        if ($type == 'phone') {
            (new MensajeroHelper())->sendCodePhone($phone, $message, $generate->code);
            return response()->json(['message' => "A verification code has been sent to the selected {$type}"]);
        }

        if ($type == 'email') {
            Mail::to($data['email'])->send(new SendVerifyEmail($user->full_name, $generate->code));
            return response()->json(['message' => "A verification code has been sent to the selected {$type}"]);
        }
    }

    /***
     * Validate if the code is still valid in time
     * Time: 30 minutes
     */
    public function confirmPhoneOrEmail($data)
    {
        if ($this->verifyToken($data['code'])) {
            return $this->postVerifyUser($data['type'], $data);
        }

        return response()->json(['message' => "Your code has expired, request a new verification code for your {$data['type']}."], 400);
    }

    /**
     * Update and return to user as verified
     *
     */
    protected function postVerifyUser($type, $data)
    {
        if ($type == 'phone') {
            return response()->json([
                'phone' => $data['phone'],
                'is_verified' => true,
            ]);
        }

        if ($type == 'email') {
            return response()->json([
                'email' => $data['email'],
                'is_verified' => true,
            ]);
        }
    }
}
