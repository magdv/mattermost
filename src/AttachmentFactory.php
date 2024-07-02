<?php

namespace App;

final class AttachmentFactory
{
    public static function alertAttachment(string $title, Text $description): Attachment
    {
        return (new Attachment())
            ->setErrorColor()
            ->setPretext($title)
            ->setText($description->getText());
    }

    public static function successAttachment(string $title, Text $description): Attachment
    {
        return (new Attachment())
            ->setSuccessColor()
            ->setPretext($title)
            ->setText($description->getText());
    }
}
