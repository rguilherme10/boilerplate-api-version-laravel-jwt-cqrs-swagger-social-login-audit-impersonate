<?php

use Modules\ChatAI\App\Events\GeminiStreamChunk;
use WebSocket\Client;

class GeminiStreamService
{
    public function streamMessage(string $message)
    {
        $client = new Client(
            "wss://generativelanguage.googleapis.com/ws/google.ai.generativelanguage.v1alpha.GenerativeService.BidiGenerateContent?key=" . config('services.gemini.key'),
            ['verify' => false ]
        );

        $client->send(json_encode([
            'model' => 'models/gemini-pro',
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $message]]]
            ]
        ]));

        while ($client->isConnected()) {
            $chunk = $client->receive();
            broadcast(new GeminiStreamChunk($chunk));
        }
    }
}