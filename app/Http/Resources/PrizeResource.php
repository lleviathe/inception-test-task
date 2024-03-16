<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrizeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'amount' => $this->amount,
            'rank_groups' => RankGroupResource::collection($this->whenLoaded('rankGroups')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
