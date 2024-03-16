<?php

namespace App\Http\Requests\Rank;

use Illuminate\Foundation\Http\FormRequest;

class StoreRankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'rank_group_id' => 'sometimes|exists:rank_groups,id',
        ];
    }
}
