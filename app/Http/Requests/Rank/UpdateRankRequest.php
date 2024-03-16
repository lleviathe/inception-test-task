<?php

namespace App\Http\Requests\Rank;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'rank_group_id' => 'exists:rank_groups,id',
        ];
    }
}
