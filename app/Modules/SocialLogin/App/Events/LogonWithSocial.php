<?php

namespace Modules\SocialLogin\App\Events;

use Illuminate\Queue\SerializesModels;
use Modules\SocialLogin\App\Models\SocialUser;

class LogonWithSocial
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public SocialUser $social_user)
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
