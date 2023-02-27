<?php

namespace App\Service;

use MessageBird\Client;
use MessageBird\Objects\Message;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MessageBirdService
{
    private Client $client;
    private ParameterBagInterface $parameterBag;
    public  function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->client = new Client($this->parameterBag->get('message_bird_key'));
    }

    public function sendSMS(int $recipient, int $sender)
    {
        $Message = new \MessageBird\Objects\Message();
        $Message->originator = 'TestMessage';
        $Message->recipients = array(+237695254870);
        $Message->body = 'This is a test message';

//        $response = $this->client->messages->create($Message);
    }

    private function messageObject(): Message
    {
        return new Message();
    }
}