<?php

namespace App;

use Psr\Http\Client\ClientInterface;

class MatermostClient implements MattermostClientInterface
{
    public function __construct(
        private ClientInterface $client,
        private string $webHookUrl,
        private string $username
    ) {
    }

    public function send(Message $message): void
    {
        $requestPayload = $message->toArray();
        $requestPayload['username'] = $this->username;

        try {
            $response = $this->client->request(
                'POST',
                $this->webHookUrl,
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'body' => JsonHelper::jsonEncode($requestPayload, true),
                    'timeout' => 5
                ]
            );
        } catch (\Throwable $e) {
            throw new MattermostException('Ошибка клиента: ' . $e->getMessage());
        }

        if (!$this->isOk($response->getStatusCode())) {
            throw new MattermostException('Ошибка при отправке запроса: ' . $response->getBody());
        }
    }

    private function isOk(int $code): bool
    {
        return $code >= 200 && $code < 300;
    }

    public function batchSend(Message ...$message): void
    {
        foreach ($message as $item) {
            $this->send($item);
        }
    }
}
