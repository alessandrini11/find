<?php

namespace App\DTO;

class Response
{
    public string $message;
    public int $code;
    public bool $success;

    public function __construct(string $message = 'Success', bool $success = true, int $code = 200)
    {
        $this->message = $message;
        $this->success = $success;
        $this->code = $code;
    }
}