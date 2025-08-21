<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeminiStreamChunk implements ShouldBroadcast
{
    public $chunk;

    public function __construct($chunk)
    {
        $this->chunk = $chunk;
    }

    public function broadcastOn()
    {
        return new Channel('chat-stream');
    }
}
