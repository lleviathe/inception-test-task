<?php

namespace Database\Seeders;

use App\Models\Prize;
use App\Models\Rank;
use App\Models\RankGroup;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
        ]);

        $rankGroups = RankGroup::factory(3)->create();

        // Create ranks
        $rankGroups->each(function ($rankGroup) {
            $rankGroup->ranks()->saveMany(
                Rank::factory(4)->make()
            );
        });


        // Create prizes
        $rankGroups->each(function ($rankGroup) {
            $rankGroup->prizes()->saveMany(
                Prize::factory(3)->make()
            );
        });
    }
}
