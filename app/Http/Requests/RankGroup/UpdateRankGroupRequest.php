<?php

namespace App\Http\Requests\RankGroup;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRankGroupRequest extends FormRequest
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
