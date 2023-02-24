<?php

namespace App\Service;

use MessageBird\Client;
use MessageBird\Objects\Message;

class MessageBirdService
{
    private Client $client;
    public  function __construct()
    {
        $this->client = new Client('PRmJiqKYeABbd7Prq48yfn5rE');
    }

    public function sendSMS(int $recipient, int $sender)
    {
        $message = $this->messageObject();
        $message->originator = 'Find App';
        $message->recipients = [$recipient];
        $message->body = "L'utilisateur {$sender} vous contactera pour entrer en posséssion du document";

        $response = $this->client->messages->create($message);
        dd($response);
    }

    private function messageObject(): Message
    {
        return new Message();
    }
}