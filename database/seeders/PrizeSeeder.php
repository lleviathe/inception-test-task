<?php

namespace Database\Seeders;

use App\Models\Prize;
use App\Models\RankGroup;
use Illuminate\Database\Seeder;

class PrizeSeeder extends Seeder
{
    public function run(): void
    {
        $rankGroups = RankGroup::all();

        $rankGroups->each(function (RankGroup $rankGroup) {
            $prizes = Prize::factory(3)->create(); // Теперь призы сохраняются в базу данных и имеют ID
            $prizes->each(function ($prize) use ($rankGroup) {
                $rankGroup->prizes()->attach($prize->id, ['number' => fake()->numberBetween(10, 100)]);
            });
        });
    }
}
