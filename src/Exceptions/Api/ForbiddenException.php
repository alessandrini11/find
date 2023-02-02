<?php

namespace App\Exceptions\Api;

use Symfony\Component\HttpFoundation\Response;

class ForbiddenException extends ApiException
{

    public function __construct($message = 'Forbidden')
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message);
    }
}