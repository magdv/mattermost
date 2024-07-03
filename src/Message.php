<?php

namespace MagDv\Mattermost;

final class Message
{
    /**
     * The text of the message.
     */
    private string $text = '';

    /**
     * The icon of the message.
     */
    private string $iconUrl = '';

    /**
     * The attachments of the message.
     * @var Attachment[]
     */
    private array $attachments = [];

    /** Канал для публикации  */
    private ?string $channel = null;

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setIconUrl(string $iconUrl): self
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * Override all attachments for the message.
     *
     * @param Attachment[] $attachments
     * @return $this
     */
    public function setAttachments(array $attachments = []): self
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Add an attachment for the message
     * @param Attachment $attachment
     * @return Message
     */
    public function addAttachment(Attachment $attachment): self
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-return array{
     * text:string,
     * icon_url:string,
     * attachments:array{
     * array{
     * fallback:string,
     * color:string,
     * pretext:string,
     * text:string,
     * author_name:string,
     * author_link:string,
     * author_icon:string,
     * title:string,
     * title_link:string,
     * fields:string,
     * image_url:string,
     * thumb_url:string,
     * actions:array
     * }
     * }}
     */
    public function toArray(): array
    {
        /** @psalm-suppress InvalidReturnStatement */
        return array_filter(
            [
                'text' => $this->text,
                'channel' => $this->channel,
                'icon_url' => $this->iconUrl,
                'attachments' => array_map(
                    static function (Attachment $attachment) {
                        return $attachment->toArray();
                    },
                    $this->attachments
                ),
            ]
        );
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }
}
