<?php

namespace App\Http\Requests\Prize;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignPrizeToRankGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'prize_id' => [
                'required',
                'exists:prizes,id',
                Rule::unique('prize_rank_group')->where(function ($query) {
                    return $query
                        ->where('prize_id', $this->prize_id)
                        ->where('rank_group_id', $this->rank_group_id);
                })],
            'number' => 'required|integer|min:1',
        ];
    }
}
