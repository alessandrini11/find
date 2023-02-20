<?php

namespace App\Exceptions\Api;

use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends ApiException
{
    public function __construct(int $statusCode = Response::HTTP_UNAUTHORIZED, string $message = 'Unauthorized')
    {
        parent::__construct($statusCode, $message);
    }
}