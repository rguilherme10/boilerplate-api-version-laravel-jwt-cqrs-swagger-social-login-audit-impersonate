<?php

namespace Modules\SocialLogin\App\Listeners;

use Illuminate\Support\Facades\Cache;
use Modules\SocialLogin\App\Events\LogonWithSocial;

class SocialUserCleanCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LogonWithSocial $event): void
    {
        Cache::forget("socialUser:user_id:{$event->social_user->user_id}:provider:{$event->social_user->oauth_type}");
    }
}
