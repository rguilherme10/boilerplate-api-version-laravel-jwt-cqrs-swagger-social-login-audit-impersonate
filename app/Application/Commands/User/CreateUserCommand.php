<?php 
namespace App\Application\Commands\User;

class CreateUserCommand
{
    public string $name;
    public string $email;
    public string $password;
    public string $email_verified_at;

    public function __construct(string $name, string $email, string $password, string|null $email_verified_at = null)
    {
        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
        $this->email_verified_at = $email_verified_at;
    }
}