<?php
namespace Modules\ChatAI\App\Services;

class GeminiService implements IGeminiService
{
    protected $baseUrl;
    protected $client;

    public function __construct()
    {
        $apiKey = config('services.gemini.key');
        $this->baseUrl = config('services.gemini.url');
        $this->client = new \GuzzleHttp\Client([
            //'base_uri' => $this->baseUrl,
            \GuzzleHttp\RequestOptions::HEADERS => [
                'X-goog-api-key' => $apiKey,
            ],
            'defaults'                          => [
                //\GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 5,
                \GuzzleHttp\RequestOptions::ALLOW_REDIRECTS => true,
            ],
            \GuzzleHttp\RequestOptions::VERIFY  => false,
        ]);
    }

    public function sendMessage(string $message)
    {
        $response = $this->client->post($this->baseUrl, [
                'json' => [
                    'contents' => [
                        ['role' => 'user', 'parts' => [['text' => $message]]]
                    ]
                ]
            ]);
        $bodyresponse = $response->getBody();
        $result = json_decode($bodyresponse, true);
        return $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Erro na resposta';
    }

    public function sendWithHistory(array $history)
    {
        $response = $this->client->post($this->baseUrl, [
                'json' => [
                    'contents' => $history
                ]
            ]);

        $bodyresponse = $response->getBody();
        $result = json_decode($bodyresponse, true);
        return $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Erro na resposta';
    }
}