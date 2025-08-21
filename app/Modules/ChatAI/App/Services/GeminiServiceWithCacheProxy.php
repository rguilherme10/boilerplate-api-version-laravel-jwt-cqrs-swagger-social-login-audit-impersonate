<?php
namespace Modules\ChatAI\App\Services;

use Illuminate\Support\Facades\Cache;

class GeminiServiceWithCacheProxy implements IGeminiService
{
    public function sendMessage(string $message)
    {
        //gera keywords para fazer cache
        $keywords = extrairPalavrasChave($message);

        //Cache por 5 dias
        $response = Cache::remember("gemini:response:".hash('sha1', json_encode($keywords)), now()->addDays(5), function () use ($message) {
            //Envia para Gemini
            return app(GeminiService::class)->sendMessage($message);
        });

        return $response;
    }

    public function sendWithHistory(array $history)
    {
        $texto_user = implode(' ', array_merge(...array_map(function ($item) {
            if ($item['role'] === 'user') {
                return array_map(fn($part) => $part['text'] ?? '', $item['parts']);
            }
            return [];
        }, $history)));

        //gera keywords para fazer cache
        $keywords = extrairPalavrasChave($texto_user);

        //Cache por 5 dias
        $response = Cache::remember("gemini:response:".hash('sha1', json_encode($keywords)), now()->addDays(5), function () use ($history) {
            //Envia para Gemini
            return app(GeminiService::class)->sendWithHistory($history);
        });

        return $response;
    }
}