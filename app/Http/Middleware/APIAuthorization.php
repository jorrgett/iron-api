<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\External;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class APIAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secretKey = $request->header('secret-key');

        if (External::verify($secretKey)->first()) {
            return $next($request);
        }

        return response()->json([
            'errors' => 'The secret key entered is invalid'
        ], 401);
    }
}
