<?php

namespace App\Application\Queries\User;

class GetUserByIdQuery
{
    public function __construct(public int $id)
    {
    }
}