<?php

namespace App\Application\Queries\User;

class GetUserByEmailQuery
{
    public string $email;
    public function __construct(int $email)
    {
        $this->email = $email;
    }
}