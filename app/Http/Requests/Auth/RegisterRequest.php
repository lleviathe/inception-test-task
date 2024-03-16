<?php

namespace App\Http\Requests\Auth;

use App\Enums\GenderEnum;
use App\Enums\LangEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ! auth()->check();
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => ['required', new Enum(GenderEnum::class)],
            'lang' => ['required', new Enum(LangEnum::class)],
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
