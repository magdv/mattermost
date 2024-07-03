<?php

namespace MagDv\Mattermost;

use Nyholm\Psr7\Request;
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

        $req = new Request(
            'POST',
            $this->webHookUrl,
            [
                'Content-Type' => 'application/json',
                'timeout' => 5
            ],
            JsonHelper::jsonEncode($requestPayload, true)
        );

        try {
            $response = $this->client->sendRequest(
                $req
            );
        } catch (\Throwable $e) {
            throw new MattermostException('Ошибка клиента: ' . $e->getMessage());
        }

        if (!$this->isOk($response->getStatusCode())) {
            throw new MattermostException('Ошибка при отправке запроса: ' . $response->getBody()->getContents());
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
