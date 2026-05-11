<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    public function __construct(
        string $message = 'An error occurred.',
        protected int $statusCode = 400,
        protected array $errors = []
    ) {
        parent::__construct($message, $statusCode);
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(): JsonResponse
    {
        $response = [
            'message' => $this->getMessage(),
        ];

        if (!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }

        return response()->json($response, $this->statusCode);
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
