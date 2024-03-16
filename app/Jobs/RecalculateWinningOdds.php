<?php

namespace App\Jobs;

use App\Models\RankGroup;
use App\Services\PrizeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateWinningOdds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly RankGroup $rankGroup,
    ) {
    }

    public function handle(): void
    {
        PrizeService::recalculateWinningOddsForRankGroup($this->rankGroup);
    }
}
