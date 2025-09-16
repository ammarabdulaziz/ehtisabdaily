<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountType>
 */
class AccountTypeFactory extends Factory
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
            'name' => $this->faker->randomElement(['Cash-in-Hand', 'Bank Account', 'Savings Account', 'Investment Account']),
            'description' => $this->faker->optional()->sentence(),
            'is_default' => false,
        ];
    }
}
