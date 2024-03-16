<?php

namespace App\Jobs;

use App\Models\Prize;
use App\Models\RankGroup;
use App\Services\PrizeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignPrizeToRankGroup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $prizeId,
        private readonly int $rankGroupId,
        private readonly int $number,
    )
    {
    }

    public function handle(): void
    {
        $prize = Prize::findOrFail($this->prizeId);
        $rankGroup = RankGroup::findOrFail($this->rankGroupId);

        if ($prize->rankGroups->contains($rankGroup)) {
            return;
        }

        app(PrizeService::class)->assignPrizeToRankGroup($prize, $rankGroup, $this->number);
    }
}
