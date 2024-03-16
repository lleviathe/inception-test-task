<?php

namespace App\Http\Requests\Prize;

use App\Enums\PrizeTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePrizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'type' => ['required', new Enum(PrizeTypeEnum::class)],
            'description' => 'required|string',
            'amount' => 'sometimes|numeric',
        ];
    }
}
