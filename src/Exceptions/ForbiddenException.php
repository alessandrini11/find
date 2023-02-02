<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenException extends HttpException
{

    public function __construct(int $status = Response::HTTP_FORBIDDEN, string $message = 'Forbidden')
    {
        parent::__construct($status, $message);
    }
}