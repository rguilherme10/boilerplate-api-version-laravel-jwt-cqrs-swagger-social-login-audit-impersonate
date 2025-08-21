<?php

namespace App\Queries;

class GetUserByIdQuery
{
    public int $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}