<?php

namespace App\Actions;

use Str;

/**
 * Usage:
 *      $result = Result::success('Operasi sukses dilakukan', $data)
 *      if ($result->success()) {
 *          return json_encode($data);
 *      } else {
 *          return abort(404, "data tidak ditemukan");
 *      }
 */
class Result
{
    private $availableStatus = ['success', 'error'];

    private ?string $status = null;
    private ?string $message = null;
    private $data = null;

    public function __construct($status)
    {
        if (in_array($status, $this->availableStatus)) {
            $this->status = $status;
        } else {
            throw new \RuntimeException('Invalid status');
        }
    }

    // get status
    public function status(): bool
    {
        return $this->status == 'success';
    }

    // set/get result message
    public function message($message = null)
    {
        if (func_num_args() > 0) {
            $this->message = $message;
            return $this;
        } else {
            return $this->message;
        }
    }

    // set/get result payload data
    public function data($data = null)
    {
        if (func_num_args() > 0) {
            $this->data = $data;
            return $this;
        } else {
            return $this->data;
        }
    }

    // output Result attribute sebagai array
    public function toArray(): array
    {
        return [
            'status' => $this->status(),
            'message' => $this->message(),
            'data' => $this->data(),
        ];
    }

    /**
     * Handle call:
     *      echo "RESULT {$result}"
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Handle call:
     *      $result->isSuccess() or $result->success()
     *      $result->isError() or $result->error()
     */
    public function __call($method, $parameters): bool
    {
        if (count($parameters) == 0) {
            // handle $result->success(), $result->error()
            if (in_array($method, $this->availableStatus)) {
                return $method == $this->status;
            }

            // handle $result->isSuccess(), $result->isError()
            // $result->isSucess() equivalent to $result->success()
            $m = Str::of($method)->ltrim('is')->lower();
            if (in_array($m, $this->availableStatus)) {
                return $m == $this->status;
            }
        }

        throw new \BadMethodCallException();
    }

    /**
     * Handle call:
     *      Result::success('Operasi Sukses', $data)
     *      Result::error('Operasi Gagal', $data)
     */
    public static function __callStatic($method, $parameters): self
    {
        // return (new static)->$method(...$parameters);

        if ($parameters) {
            // call Result::sucess($msg), Result::error($msg), etc.
            return static::new($method, $parameters[0]);
        } else {
            // call Result::sucess(), Result::error(), etc.
            return static::new($method);
        }
    }

    private static function new($status, $message = null)
    {
        return (new static($status))->message($message);
    }
}