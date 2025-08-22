<?php 
namespace App\Application\Commands\User;

class CreateUserCommand
{
    public function __construct(
        public string $name, public string $email, public string $password, public string|null $email_verified_at = null)
    {
    }
}