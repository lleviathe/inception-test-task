<?php

namespace App\Models;

use App\Enums\PrizeTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prize extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'type' => PrizeTypeEnum::class,
        ];
    }

    public function rankGroups(): BelongsToMany
    {
        return $this->belongsToMany(RankGroup::class)
            ->using(PrizeRankGroup::class)
            ->withTimestamps();
    }
}
