<?php

namespace Database\Seeders;

use App\Models\DepositType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepositTypeSeeder extends Seeder
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

        // Create deposit types from SQL dump
        $depositTypesData = [
            ['id' => 1, 'name' => 'Credit Deposit', 'description' => 'Rayyan Bank', 'is_default' => false],
        ];

        foreach ($depositTypesData as $depositTypeData) {
            DepositType::updateOrCreate(
                ['id' => $depositTypeData['id']],
                [
                    'user_id' => $user->id,
                    'name' => $depositTypeData['name'],
                    'description' => $depositTypeData['description'],
                    'is_default' => $depositTypeData['is_default'],
                    'created_at' => '2025-09-22 12:30:55',
                    'updated_at' => '2025-09-22 12:30:55',
                ]
            );
        }
    }
}
