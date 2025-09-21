<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssetAccount>
 */
class AssetAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_id' => \App\Models\Asset::factory(),
            'account_type_id' => \App\Models\AccountType::factory(),
            'actual_amount' => $this->faker->randomFloat(2, 100, 10000),
            'amount' => null,
            'currency' => 'QAR',
            'exchange_rate' => 1.000000,
            'notes' => $this->faker->sentence(),
        ];
    }
}
