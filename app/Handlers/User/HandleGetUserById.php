<?php 

namespace App\Handlers;

use App\Models\User;
use App\Queries\GetUserByIdQuery;
use Illuminate\Support\Facades\Cache;

class HandleGetUserById
{
    public function handle(GetUserByIdQuery $query)
    {
        $cacheKey = "user:{$query->id}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($query) {
            return User::findOrFail($query->id);
        });
    }
}