<?php

namespace App\Services;

use App\Models\Prize;
use App\Models\PrizeRankGroup;
use App\Models\RankGroup;

class PrizeService
{
    public function assignPrizeToRankGroup(Prize $prize, RankGroup $rankGroup, int $number): void
    {
        $prize->rankGroups()->attach($rankGroup, ['number' => $number]);
        $winningOdds = self::calculateWinningOdds($prize, $rankGroup);

        cache()->forever("winning_odds:{$prize->id}:{$rankGroup->id}", $winningOdds);
    }

    public static function calculateWinningOdds(Prize $prize, RankGroup $rankGroup): float
    {
        $totalPrizes = $rankGroup->prizes->sum(fn($prize) => $prize->pivot?->number);
        $pivot = PrizeRankGroup::query()
            ->where('prize_id', $prize->id)
            ->where('rank_group_id', $rankGroup->id)
            ->sole();

        return ($pivot?->number / $totalPrizes) * 100;
    }
}
