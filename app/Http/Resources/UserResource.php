<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'lang' => $this->lang,
            'balance' => $this->balance,
            'is_blocked' => $this->is_blocked,
            'username' => $this->username,
            'rank_group_id' => $this->rank_group_id,
            'rank_group' => new RankGroupResource($this->whenLoaded('rankGroup')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
