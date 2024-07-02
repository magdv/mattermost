<?php

declare(strict_types=1);

namespace Test\Unit\Mattermost;

use App\Attachment;
use App\MatermostClient;
use App\Message;
use PHPUnit\Framework\TestCase;
use Test\Helpers\MockResponseHelper;
use Test\Helpers\Responses;

class ClientTest extends TestCase
{
    public function testSuccessRequest(): void
    {
        /** @codingStandardsIgnoreStart Generic.Files.LineLength */
        $attachment = (new Attachment())->setFallback('This is the fallback test for the attachment.')
            ->setSuccessColor();
        /** @codingStandardsIgnoreEnd */

        $message = new Message();
        $message->setText('Testing')
            ->setAttachments([$attachment]);

        $mockClient = MockResponseHelper::createClient(
            [
                Responses::successResponse()
            ]
        );

        $client = new MatermostClient(
            $mockClient,
            'http://matermost:8065/hooks/dui7ons8et8sxpuhdxxj4todfw',
            'test'
        );
        $client->send($message);

        self::assertTrue(true);
    }

    public function testBadRequest(): void
    {
        /** @codingStandardsIgnoreStart Generic.Files.LineLength */
        $attachment = (new Attachment())->setFallback('This is the fallback test for the attachment.')
            ->setSuccessColor();
        /** @codingStandardsIgnoreEnd */

        $message = new Message();
        $message->setText('Testing')->setAttachments([$attachment]);

        $mockClient = MockResponseHelper::createClient(
            [
                Responses::badResponse()
            ]
        );

        $client = new MatermostClient(
            $mockClient,
            'http://matermost:8065/hooks/dui7ons8et8sxpuhdxxj4todfw',
            'test'
        );

        $this->expectExceptionMessage('Ошибка клиента: Client error: `POST http://matermost:8065/hooks/dui7ons8et8sxpuhdxxj4todfw` resulted in a `400 Bad Request` response');

        $client->send($message);
    }

    public function testServerErrorResponse(): void
    {
        /** @codingStandardsIgnoreStart Generic.Files.LineLength */
        $attachment = (new Attachment())->setFallback('This is the fallback test for the attachment.')
            ->setSuccessColor();
        /** @codingStandardsIgnoreEnd */

        $message = new Message();
        $message->setText('Testing',)->setAttachments([$attachment]);
        $mockClient = MockResponseHelper::createClient(
            [
                Responses::internalServerErrorResponse()
            ]
        );

        $client = new MatermostClient(
            $mockClient,
            'http://matermost:8065/hooks/dui7ons8et8sxpuhdxxj4todfw',
            'test'
        );

        $this->expectExceptionMessage('Ошибка клиента: Server error: `POST http://matermost:8065/hooks/dui7ons8et8sxpuhdxxj4todfw` resulted in a `500 Internal Server Error` response');

        $client->send($message);
    }
}
