<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

class EventRegisteredTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_event_registered(): void
    {
        $user = User::factory()->create();
        Event::fake();
        event(new Registered($user));
        
        Event::assertDispatched(Registered::class);

        Event::assertListening(
            Registered::class,
            SendEmailVerificationNotification::class
        );
    }
}
