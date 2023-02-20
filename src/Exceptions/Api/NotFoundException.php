<?php

namespace App\Exceptions\Api;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends ApiException
{
    public function __construct(int $status = Response::HTTP_NOT_FOUND,string $message = 'Not Found')
    {
        parent::__construct($status, $message);
    }
}