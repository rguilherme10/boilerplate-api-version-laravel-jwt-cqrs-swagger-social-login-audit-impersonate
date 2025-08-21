<?php

namespace App\Handlers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Commands\CreateUserCommand;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class HandleCreateUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected CreateUserCommand $command;

    public function __construct(CreateUserCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::create([
            'name'     => $this->command->name,
            'email'    => $this->command->email,
            'password' => Hash::make($this->command->password),
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
            'data' => json_encode($this->command)
        ])
        ->log('Usuário criado via comando');
    }
}
