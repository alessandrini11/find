<?php

namespace App\Exceptions\Api;

use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException implements JsonSerializable
{

    public function __construct(int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, string $message = 'Internal Server Error')
    {
        parent::__construct($statusCode, $message);
    }
    public function jsonSerialize(): array
    {
        return [
            'message' => $this->getMessage(),
            'status' => $this->getStatusCode()
        ];
    }
}