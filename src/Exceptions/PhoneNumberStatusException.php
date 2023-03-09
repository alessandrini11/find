<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PhoneNumberStatusException extends HttpException
{
    public function __construct(int $statusCode = Response::HTTP_BAD_REQUEST, string $message = 'Vous devez enregistrer votre numéro de téléphone')
    {
        parent::__construct($statusCode, $message);
    }
}