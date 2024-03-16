<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RankResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'rank_group_id' => $this->rank_group_id,
            'rank_group' => new RankGroupResource($this->whenLoaded('rankGroup')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
