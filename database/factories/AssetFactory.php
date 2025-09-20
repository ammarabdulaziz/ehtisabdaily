<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(2020, 2025),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
