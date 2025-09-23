<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountType;
use App\Models\User;

class AccountTypeSeeder extends Seeder
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

        // Create account types from SQL dump
        $accountTypesData = [
            ['id' => 1, 'name' => 'Liquid Cash', 'description' => null, 'is_default' => false],
            ['id' => 2, 'name' => 'Doha Bank', 'description' => null, 'is_default' => false],
            ['id' => 3, 'name' => 'Rayyan Bank', 'description' => null, 'is_default' => false],
            ['id' => 4, 'name' => 'Federal Bank', 'description' => null, 'is_default' => false],
        ];

        foreach ($accountTypesData as $accountTypeData) {
            AccountType::updateOrCreate(
                ['id' => $accountTypeData['id']],
                [
                    'user_id' => $user->id,
                    'name' => $accountTypeData['name'],
                    'description' => $accountTypeData['description'],
                    'is_default' => $accountTypeData['is_default'],
                    'created_at' => '2025-09-22 12:28:21',
                    'updated_at' => '2025-09-22 12:28:21',
                ]
            );
        }
    }
}
