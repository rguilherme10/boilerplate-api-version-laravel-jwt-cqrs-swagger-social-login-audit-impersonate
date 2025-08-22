<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cache;

class UserCleanCache
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        Cache::forget("user:email:{$event->user->id}");
    }
}
