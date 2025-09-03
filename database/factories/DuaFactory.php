<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dua>
 */
class DuaFactory extends Factory
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
            'title' => fake()->sentence(3),
            'arabic_text' => 'بسم الله الرحمن الرحيم',
            'transliteration' => 'Bismillah ir-Rahman ir-Raheem',
            'english_translation' => fake()->sentence(10),
            'english_meaning' => fake()->paragraph(2),
            'categories' => fake()->randomElements(['Daily Duas', 'Morning Duas', 'Evening Duas', 'Food & Drink', 'Travel'], 2),
            'source' => fake()->randomElement(['Quran', 'Hadith', 'Sahih Bukhari', 'Sahih Muslim']),
            'reference' => fake()->optional()->sentence(2),
            'benefits' => fake()->optional()->paragraph(3),
            'recitation_count' => fake()->numberBetween(1, 10),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }
}
