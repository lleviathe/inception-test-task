<?php

namespace Database\Seeders;

use App\Data\WinningSnapshotData;
use App\Models\Prize;
use App\Models\Rank;
use App\Models\RankGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

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
            RankGroupSeeder::class,
            RankSeeder::class,
            PrizeSeeder::class,
            WinningSeeder::class,
        ]);
    }
}
