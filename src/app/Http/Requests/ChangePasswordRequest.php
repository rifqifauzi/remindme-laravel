<?php

namespace App\Http\Requests;

use App\Rules\NewPasswordRule;
use App\Rules\OldPasswordRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ChangePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'old_password' => ['required','string', new OldPasswordRule],
            'password' => ['required','confirmed', 'min:8', new NewPasswordRule]
        ];
    }
}
