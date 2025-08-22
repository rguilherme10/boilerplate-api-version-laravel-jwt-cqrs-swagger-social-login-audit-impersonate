<?php

namespace App\Application\Queries\User;

class GetUserByEmailQuery
{
    public function __construct(public string $email)
    {
    }
}