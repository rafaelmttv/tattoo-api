<?php

namespace App\Exceptions;

class BusinessException extends ApiException
{
    public function __construct(
        string $message = 'A business rule violation occurred.',
        int $statusCode = 422,
        array $errors = []
    ) {
        parent::__construct($message, $statusCode, $errors);
    }
}
