<?php

namespace App\Http\Resources;

use App\Actions\Result;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

class ApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public $preserveKeys = true;

    /**
     * Create new Api resource
     */
    public static function new(bool $status = true, string $message = null, $data = null): static
    {
        if (is_callable($data)) {
            try {
                $data = $data();
            } catch (ValidationException $e) {
                throw($e);
            } catch (\Throwable $e) {
                $status = false;
                if (config('app.debug')) {
                    $message = 'Exception occurred: '.$e::class;
                    $data = ['error' => $e->getMessage(), 'trace' => $e->getTrace()];
                } else {
                    $message = 'Application error';
                    $data = null;
                }
            }
        }

        if ($data instanceof Result) {
            $response = $data->toArray();
        } else {
            $response = compact('status', 'message', 'data');
        }

        return new static((object) $response);
    }
    
    /**
     * Create new Api resource
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
