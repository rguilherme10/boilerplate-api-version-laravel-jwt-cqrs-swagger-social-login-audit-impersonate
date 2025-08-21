<?php

namespace Modules\ChatAI\App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
