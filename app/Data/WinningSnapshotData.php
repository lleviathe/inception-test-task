<?php

namespace App\Data;

use App\Enums\PrizeTypeEnum;
use Spatie\LaravelData\Data;

class WinningSnapshotData extends Data
{
    public function __construct(
        public string        $prize_name,
        public string        $prize_description,
        public PrizeTypeEnum $prize_type,
        public float         $prize_amount,
        public float         $winning_odds,
    )
    {
    }
}
