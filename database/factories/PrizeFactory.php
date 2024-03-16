<?php

namespace Database\Factories;

use App\Enums\PrizeTypeEnum;
use App\Models\Prize;
use App\Models\RankGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prize>
 */
class PrizeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'type' => $this->faker->randomElement(PrizeTypeEnum::values()),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }

    public function withRankGroup(): PrizeFactory|Factory
    {
        return $this->afterCreating(function (Prize $prize) {
            $prize->rankGroups()->attach(RankGroup::factory()->create());
        });
    }
}
