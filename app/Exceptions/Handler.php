<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json(
                ['errors' => 'Token invalid or not provided'],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof QueryException) {
            return response()->json(
                ['errors' => $exception->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            abort(JsonResponse::HTTP_METHOD_NOT_ALLOWED, 'Method not allowed');
        }

        if ($exception instanceof ValidationException) {
            return response()->json(
                [
                    'errors' => $exception->getMessage(),
                    'fields' => $exception->validator->getMessageBag()->toArray()
                ],
                JsonResponse::HTTP_PRECONDITION_FAILED
            );
        }

        if ($exception instanceof TooManyRequestsHttpException) {
            return response()->json(
                ['errors' => 'You have exceeded the limit of requests per user'],
                JsonResponse::HTTP_TOO_MANY_REQUESTS
            );
        }

        if ($exception instanceof HttpException) {
            return response()->json(
                ['errors' => 'This page not found'],
                JsonResponse::HTTP_NOT_FOUND
            );
        }


        if ($exception instanceof ModelNotFoundException) {
            return response()->json(
                ['errors' => $exception->getMessage()],
                JsonResponse::HTTP_NO_CONTENT
            );
        }

        if ($exception instanceof TokenExpiredException) {
            return response()->json(['errors' => 'Token expired, please generate a new token'], 401);
        } else if ($exception instanceof JWTException) {
            return response()->json(['errors' => 'The token could not be parsed from the request'], 401);
        } else if ($exception instanceof TokenInvalidException) {
            return response()->json(['errors' => 'Invalid token, please generate a new token'], 401);
        } else if ($exception instanceof TokenBlacklistedException) {
            return response()->json(['errors' => 'Your token is blacklisted, please generate a new token'], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json(['errors' => 'You do not have permissions to execute this action'], 403);
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\PermissionDoesNotExist) {
            return response()->json(['errors' => 'Alguno de los permisos que quieres asignar no existen'], 400);
        }

        return parent::render($request, $exception);
    }
}
