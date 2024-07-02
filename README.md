
Client to work with Matermost webhook
----------------------------------------

This library will help you send messages to Mattermost by Webhook.

Installation / Usage
--------------------

Install the latest version via [composer](https://getcomposer.org/):

```bash
composer require magdv/mattermost
```

Here is an example of usage. 
--------------------

```php
        use App\Attachment;
        use App\Message;
        use App\WebhookClient;
        use App\WebhookParams;
        use GuzzleHttp\Client;
        
        // Any PSR7 Client
        $psr7Client = new Client();
        $client = new WebhookClient(
        $psr7Client, 
        'http://matermost/hooks/2222222222',
        'tester'
        );

        $attachment = (new Attachment())->setFallback('This is the fallback test for the attachment.')
            ->setSuccessColor()
            ->setPretext('This is optional pretext that shows above the attachment.')
            ->setText('This is the text. **Finaly!** :let_me_in: ');
            
        // you can add array of attachments
        $message = new Message();
        $message->setText('Testing Mattermost client')
            ->setAttachments([$attachment])
            ->setChannel('town-square');


        $client->send($message);
        // or
        $client->batchSend([$message]);
```

If you need to send huge text, more than 4000 symbols, you can use 
```php 
    $messages = MessageHelper::createMessagesWithTextAttachments(
    'channelName'
    'huge text, longer 4000 symbols'
    );
    $client->batchSend(...$messages);
```
It will create Message[], which you can send.
It will break text by pages and qoute it by \```
So you just send it and it will print at chat one after another. 
