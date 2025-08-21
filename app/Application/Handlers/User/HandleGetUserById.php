<?php 

namespace App\Application\Handlers;

use App\Models\User;
use App\Application\Queries\GetUserByIdQuery;
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