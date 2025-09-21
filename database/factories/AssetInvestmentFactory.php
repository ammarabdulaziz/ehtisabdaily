<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssetInvestment>
 */
class AssetInvestmentFactory extends Factory
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
            'investment_type_id' => \App\Models\InvestmentType::factory(),
            'actual_amount' => $this->faker->randomFloat(2, 1000, 20000),
            'amount' => null,
            'currency' => 'QAR',
            'exchange_rate' => 1.000000,
            'notes' => $this->faker->sentence(),
        ];
    }
}
