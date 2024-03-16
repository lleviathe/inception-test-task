<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PrizeRankGroup extends Pivot
{
    protected $fillable = [
        'prize_id',
        'rank_group_id',
        'number',
    ];
}
