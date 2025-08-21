<?php
namespace Modules\ChatAI\App\Services;

interface IGeminiService
{
    /**
     * Envia uma mensagem simples para o serviço Gemini.
     *
     * @param string $message
     * @return mixed
     */
    public function sendMessage(string $message);

    /**
     * Envia uma mensagem com histórico de conversas.
     *
     * @param array $history
     * @return mixed
     */
    public function sendWithHistory(array $history);
}