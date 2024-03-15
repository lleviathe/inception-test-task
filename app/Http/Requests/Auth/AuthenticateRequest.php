<?php

namespace App\Http\Requests\Auth;

use App\Enums\AuthenticableTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AuthenticateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->check();
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', new Enum(AuthenticableTypeEnum::class)],
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
