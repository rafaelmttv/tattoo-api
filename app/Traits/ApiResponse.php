<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    /**
     * Return a success response (200).
     */
    protected function successResponse(
        JsonResource|ResourceCollection|array $data,
        string $message = null
    ): JsonResponse {
        $response = $data instanceof JsonResource || $data instanceof ResourceCollection
            ? $data->response()
            : response()->json(['data' => $data]);

        if ($message) {
            $response->setData(
                array_merge(
                    (array) json_decode($response->getContent(), true),
                    ['message' => $message]
                )
            );
        }

        return $response->setStatusCode(200);
    }

    /**
     * Return a created response (201).
     */
    protected function createdResponse(
        JsonResource $data,
        string $message = 'Resource created successfully.'
    ): JsonResponse {
        return $data
            ->response()
            ->setStatusCode(201)
            ->setData(
                array_merge(
                    (array) json_decode($data->response()->getContent(), true),
                    ['message' => $message]
                )
            );
    }

    /**
     * Return a no content response (204).
     */
    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Return an error response.
     */
    protected function errorResponse(
        string $message,
        int $statusCode = 400,
        array $errors = []
    ): JsonResponse {
        $response = [
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
