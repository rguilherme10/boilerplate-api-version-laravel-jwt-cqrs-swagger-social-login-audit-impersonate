<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->baseUrl = config('services.gemini.url');
    }

    public function sendMessage(string $message)
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl, [
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $message]]]
                ]
            ]);

        return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Erro na resposta';
    }

    public function sendWithHistory(array $history)
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl, [
                'contents' => $history
            ]);

        return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Erro na resposta';
    }
}