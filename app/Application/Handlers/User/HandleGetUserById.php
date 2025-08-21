<?php 

namespace App\Application\Handlers\User;

use App\Models\User;
use App\Application\Queries\User\GetUserByIdQuery;
use Illuminate\Support\Facades\Cache;

class HandleGetUserById
{
    public function handle(GetUserByIdQuery $query)
    {
        $cacheKey = "user:{$query->id}";

        // Auditoria com impersonação
        $realUser = auth()->user();
        $impersonatedUser = app()->has('impersonated_user') ? app('impersonated_user') : null;

        $user = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($query) {
            return User::findOrFail($query->id);
        });

        activity()
        ->causedBy($realUser)
        ->performedOn($user)
        ->withProperties([
            'impersonated_as' => $impersonatedUser?->id,
            'impersonated_name' => $impersonatedUser?->name,
            'query' => 'GetUserByIdQuery',
            'data' => json_encode($query)
        ])
        ->log('Usuário consultado via HandleGetUserById');

        return $user;
    }
}