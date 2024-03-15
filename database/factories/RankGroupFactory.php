<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RankGroup>
 */
class RankGroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
