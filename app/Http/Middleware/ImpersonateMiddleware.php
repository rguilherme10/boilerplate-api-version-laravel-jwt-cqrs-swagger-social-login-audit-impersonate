<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ImpersonateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $impersonatedId = $payload->get('impersonated_id');

            if ($impersonatedId) {
                $impersonatedUser = User::find($impersonatedId);
                if ($impersonatedUser) {
                    app()->instance('impersonated_user', $impersonatedUser);
                }
            }
        } catch (\Exception $e) {
            // Token inválido ou ausente
        }

        return $next($request);
    }
}
