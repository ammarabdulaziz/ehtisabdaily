<?php

namespace Database\Seeders;

use App\Models\InvestmentType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $this->command->error('No user found. Please run UserSeeder first.');
            return;
        }

        // Create investment types from SQL dump
        $investmentTypesData = [
            ['id' => 1, 'name' => 'Mutual Funds', 'description' => null, 'is_default' => false],
            ['id' => 2, 'name' => 'MCBS', 'description' => null, 'is_default' => false],
            ['id' => 3, 'name' => 'Malabar Gold', 'description' => null, 'is_default' => false],
            ['id' => 4, 'name' => 'Boutique', 'description' => null, 'is_default' => false],
            ['id' => 5, 'name' => 'Kuri', 'description' => null, 'is_default' => false],
        ];

        foreach ($investmentTypesData as $investmentTypeData) {
            InvestmentType::updateOrCreate(
                ['id' => $investmentTypeData['id']],
                [
                    'user_id' => $user->id,
                    'name' => $investmentTypeData['name'],
                    'description' => $investmentTypeData['description'],
                    'is_default' => $investmentTypeData['is_default'],
                    'created_at' => '2025-09-22 12:30:10',
                    'updated_at' => '2025-09-22 12:30:10',
                ]
            );
        }
    }
}
