<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RankGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function ranks(): HasMany
    {
        return $this->hasMany(Rank::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function prizes(): BelongsToMany
    {
        return $this->belongsToMany(Prize::class)
            ->using(PrizeRankGroup::class)
            ->withPivot('number')
            ->withTimestamps();
    }
}
