<?php

namespace App\Http\Response;

use App\Enums\ResponseMessageEnum;
use App\Traits\SendsResponse;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;


class BaseResponse implements Responsable
{
    use SendsResponse;

    public function __construct(
        public readonly ResponseMessageEnum $message,
        public readonly mixed $content,
        public readonly int $status = Response::HTTP_OK,
    ) {}
}
