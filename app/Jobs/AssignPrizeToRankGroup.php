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
        private readonly int       $prize_id,
        private readonly int       $number,
        private readonly RankGroup $rankGroup,
    )
    {
    }

    public function handle(): void
    {
        $prize = Prize::findOrFail($this->prize_id);

        if ($prize->rankGroups->pluck('id')->contains($this->rankGroup->id)) {
            return;
        }

        app(PrizeService::class)->assignPrizeToRankGroup($prize, $this->rankGroup, $this->number);
    }
}
