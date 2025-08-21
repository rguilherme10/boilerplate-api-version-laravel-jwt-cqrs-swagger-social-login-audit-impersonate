<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\CreateUserCommand;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class HandleCreateUser
{
    public function handle(CreateUserCommand $command): User
    {
        $user = User::create([
            'name'     => $command->name,
            'email'    => $command->email,
            'password' => Hash::make($command->password),
        ]);

        // Auditoria com impersonação
        $realUser = auth()->user();
        $impersonatedUser = app()->has('impersonated_user') ? app('impersonated_user') : null;

        activity()
        ->causedBy($realUser)
        ->performedOn($user)
        ->withProperties([
            'impersonated_as' => $impersonatedUser?->id,
            'impersonated_name' => $impersonatedUser?->name,
            'command' => 'CreateUserCommand',
            'data' => json_encode($command)
        ])
        ->log('Usuário criado via HandleCreateUser');

        return $user;
    }
}
