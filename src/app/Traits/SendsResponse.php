<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * @property-read string $message
 * @property-read mixed $content
 * @property-read int $status
 */
trait SendsResponse
{

    public function toResponse($request): JsonResponse
    {
        $data = [
            "message" => $this->message,
            "content" => $this->content,
        ];

        return new JsonResponse(
            data: $data,
            status: $this->status,
        );
    }
}
