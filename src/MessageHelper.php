<?php

declare(strict_types=1);

namespace App;

class MessageHelper
{
    /**
     *  Данный текст будет помещен в аттачменты
     * @param string $attachmentText
     * Заголовок аттачмента
     * @param string $attachmentTitle
     * Это текст сообщения
     * @param string $messageText
     * Канал для публикации
     * @param string $channel
     *
     * @return Message[]
     * @psalm-return array<int, Message>
     */
    public static function createMessagesWithTextAttachments(
        string $channel,
        string $attachmentText,
        string $attachmentTitle = '',
        string $messageText = '',
    ): array {
        $messages = [];

        $page = 1;
        $textItems = self::makeQuotedText($attachmentText);
        $textItemsCount = count($textItems);

        foreach ($textItems as $item) {
            $message = new Message();
            $message->setChannel($channel);
            $prefix = '';
            if ($textItemsCount > 1) {
                $prefix = 'Page ' . $page . '/' . $textItemsCount . '. ';
                $page++;
            }

            $message->setText($prefix . $messageText);

            $attachment = AttachmentFactory::alertAttachment($attachmentTitle, $item);

            $messages[] = $message->addAttachment($attachment);
        }

        return $messages;
    }

    /**
     * Вернет массив моделей Text с длиной сообщений не более 4000 символов
     *
     * @param string $text
     * @param int $maximumTextLength
     * @return Text[]
     */
    public static function makeQuotedText(string $text, int $maximumTextLength = 4000): array
    {
        $messages = [];
        // Такой хитрый разделитель чтобы случайно, не было совпадений
        $delimeter = '<<;>>';
        // отнимаем 8 символов на перенос и кавычки
        $reservedLength = 8;

        $chunked = chunk_split($text, $maximumTextLength - $reservedLength, $delimeter);
        $textArray = array_filter(explode($delimeter, $chunked));

        foreach ($textArray as $item) {
            $messages[] = self::createQuoteText($item);
        }

        return $messages;
    }

    private static function createQuoteText(string $text): Text
    {
        $textObject = new Text();
        $textObject->addLine('```');
        $textObject->addLine($text);
        $textObject->addLine('```');
        return $textObject;
    }

    public static function generateRandomString(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
