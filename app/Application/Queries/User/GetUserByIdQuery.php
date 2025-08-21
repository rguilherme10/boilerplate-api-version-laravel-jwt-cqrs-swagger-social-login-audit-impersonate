<?php

namespace App\Application\Queries\User;

class GetUserByIdQuery
{
    public int $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}