<?php

declare(strict_types=1);

namespace Test\Unit\Mattermost;

use MagDv\Mattermost\Attachment;
use MagDv\Mattermost\MatermostClient;
use MagDv\Mattermost\Message;
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
            'http://matermost:8065/hooks/efda5cy35tyax89x3edegtbjjo',
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
            'http://matermost:8065/hooks/efda5cy35tyax89x3edegtbjjo',
            'test'
        );

        $this->expectExceptionMessage('Ошибка при отправке запроса: какой - то ответ');

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
            'http://matermost:8065/hooks/efda5cy35tyax89x3edegtbjjo',
            'test'
        );

        $this->expectExceptionMessage('Ошибка при отправке запроса: какой - то ответ');

        $client->send($message);
    }
}
