<?php

namespace Database\Seeders;

use App\Data\WinningSnapshotData;
use App\Models\RankGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class WinningSeeder extends Seeder
{
    public function run(): void
    {
        $rankGroups = RankGroup::all();

        $rankGroups->each(function (RankGroup $rankGroup) {
            $rankGroup->users->each(function (User $user) use ($rankGroup) {
                $prize = $rankGroup->prizes->random();
                $user->winnings()->create([
                    'prize_id' => $prize->id,
                    'snapshot_data' => WinningSnapshotData::from([
                        'prize_name' => $prize->name,
                        'prize_description' => $prize->description,
                        'prize_type' => $prize->type,
                        'prize_amount' => $prize->amount,
                        'winning_odds' => fake()->randomNumber(2, true),
                    ]),
                ]);
            });
        });
    }
}
