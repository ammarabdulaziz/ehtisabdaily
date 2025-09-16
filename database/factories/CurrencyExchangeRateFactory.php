<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CurrencyExchangeRate>
 */
class CurrencyExchangeRateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_currency_id' => \App\Models\Currency::factory(),
            'to_currency_id' => \App\Models\Currency::factory(),
            'rate' => $this->faker->randomFloat(6, 0.000001, 999999.999999),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'source' => $this->faker->optional()->randomElement(['Manual', 'API', 'Bank']),
        ];
    }
}
