<?php

namespace App\Http\Requests\RankGroup;

use Illuminate\Foundation\Http\FormRequest;

class StoreRankGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
