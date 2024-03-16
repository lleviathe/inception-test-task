<?php

namespace Database\Seeders;

use App\Models\RankGroup;
use Illuminate\Database\Seeder;

class RankGroupSeeder extends Seeder
{
    public function run(): void
    {
        RankGroup::factory(3)->create();
    }
}
