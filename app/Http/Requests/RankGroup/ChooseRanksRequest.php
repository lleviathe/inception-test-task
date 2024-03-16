<?php

namespace App\Http\Requests\RankGroup;

use Illuminate\Foundation\Http\FormRequest;

class ChooseRanksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rank_ids' => 'required|array',
            'rank_ids.*' => 'integer|exists:ranks,id',
        ];
    }
}
