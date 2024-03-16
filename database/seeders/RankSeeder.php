<?php

namespace Database\Seeders;

use App\Models\Rank;
use App\Models\RankGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rankGroups = RankGroup::all();

        $rankGroups->each(function ($rankGroup) {
            $rankGroup->ranks()->saveMany(
                Rank::factory(4)->make()
            );

            $rankGroup->users()->saveMany(
                User::factory(5)->make()
            );
        });
    }
}
