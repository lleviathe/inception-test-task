<?php

namespace App\Services;

use App\Data\WinningSnapshotData;
use App\Exceptions\NoPrizesAssignedToRankGroupException;
use App\Exceptions\UserDoesNotHaveRankGroupException;
use App\Jobs\RecalculateWinningOdds;
use App\Models\Prize;
use App\Models\PrizeRankGroup;
use App\Models\RankGroup;
use App\Models\User;
use App\Models\Winning;

class PrizeService
{
    public function assignPrizeToRankGroup(Prize $prize, RankGroup $rankGroup, int $number): void
    {
        $rankGroup->prizes()->attach($prize->id, ['number' => $number]);

        RecalculateWinningOdds::dispatch($rankGroup);
    }

    /**
     * Get a prize for user that is spinning the wheel
     *
     * @param User $user The user who is spinning the wheel
     * @return Prize The prize that the user won
     *
     * @throws NoPrizesAssignedToRankGroupException
     * @throws UserDoesNotHaveRankGroupException
     */
    public function getPrize(User $user): Prize
    {
        $rankGroup = $user->rankGroup ?? throw new UserDoesNotHaveRankGroupException();
        $rankGroup->prizes->count() === 0 && throw new NoPrizesAssignedToRankGroupException();
        $prizes = $rankGroup->prizes;
        $probabilityArray = [];

        foreach ($prizes as $prize) {
            $winningOdds = $this->getOdds($rankGroup, $prize);

            for ($i = 0; $i < $winningOdds; $i++) {
                $probabilityArray[] = $prize;
            }
        }

        $wonPrize = $probabilityArray[array_rand($probabilityArray)];

        $this->createWinning($user, $wonPrize);

        return $wonPrize;
    }

    private function createWinning(User $user, Prize $prize): void
    {
        $rankGroup = $user->rankGroup;

        Winning::create([
            'user_id' => $user->id,
            'prize_id' => $prize->id,
            'snapshot_data' => WinningSnapshotData::from([
                'prize_name' => $prize->name,
                'prize_description' => $prize->description,
                'prize_type' => $prize->type,
                'prize_amount' => $prize->amount,
                'winning_odds' => cache()->get("winning_odds:$rankGroup->id:$prize->id", 0),
            ]),
        ]);
    }

    public static function calculateWinningOdds(Prize $prize, RankGroup $rankGroup): float
    {
        $totalPrizes = $rankGroup->prizes->sum(fn($prize) => $prize->pivot?->number);
        $pivot = PrizeRankGroup::query()
            ->where('prize_id', $prize->id)
            ->where('rank_group_id', $rankGroup->id)
            ->sole();

        return round(($pivot?->number / $totalPrizes) * 100, 2);
    }

    public static function recalculateWinningOddsForRankGroup(RankGroup $rankGroup): void
    {
        $prizes = $rankGroup->prizes;

        foreach ($prizes as $prize) {
            $winningOdds = self::calculateWinningOdds($prize, $rankGroup);

            cache()->forever("winning_odds:$rankGroup->id:$prize->id", $winningOdds);
        }
    }

    private function getOdds(mixed $rankGroup, mixed $prize): mixed
    {
        return cache()->get(
            "winning_odds:$rankGroup->id:$prize->id",
            function () use ($prize, $rankGroup) {
                /*
                 * Handling the case where the winning odds are not cached.
                 * Cache shouldn't be be outdated, as we dispatch RecalculateWinningOdds job
                 * every time a prize is assigned to a rank group.
                 * But we still need to handle the case where the cache is outdated.
                 *
                 * We can consider periodically refreshing the cache using a scheduled job.
                 */
                $odds = PrizeService::calculateWinningOdds($prize, $rankGroup);

                cache()->forever("winning_odds:$rankGroup->id:$prize->id", $odds);

                return PrizeService::calculateWinningOdds($prize, $rankGroup);
            }
        );
    }
}
