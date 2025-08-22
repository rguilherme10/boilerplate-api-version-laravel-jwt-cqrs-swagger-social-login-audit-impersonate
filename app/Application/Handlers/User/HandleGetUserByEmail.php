<?php 

namespace App\Application\Handlers\User;

use App\Models\User;
use App\Application\Queries\User\GetUserByEmailQuery;
use Illuminate\Support\Facades\Cache;

class HandleGetUserByEmail
{
    public function handle(GetUserByEmailQuery $query)
    {
        $cacheKey = "user:email:{$query->email}";

        // Auditoria com impersonação
        $realUser = auth('api')->user();
        $impersonatedUser = app()->has('impersonated_user') ? app('impersonated_user') : null;

        $user = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($query) {
            return User::where('email', $query->email)->first();
        });

        activity()
        ->causedBy($realUser)
        ->performedOn($user)
        ->withProperties([
            'impersonated_as' => $impersonatedUser?->id,
            'impersonated_name' => $impersonatedUser?->name,
            'query' => 'GetUserByEmailQuery',
            'data' => json_encode($query)
        ])
        ->log('Usuário consultado via HandleGetUserByEmail');

        return $user;
    }
}