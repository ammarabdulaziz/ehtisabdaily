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
        $defaultAccountTypes = [
            'Cash-in-Hand',
            'Doha Bank',
            'Rayyan Bank',
            'Federal Bank',
        ];

        // Create default account types for all existing users
        User::all()->each(function ($user) use ($defaultAccountTypes) {
            foreach ($defaultAccountTypes as $accountType) {
                AccountType::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $accountType,
                    ],
                    [
                        'description' => "Default {$accountType} account type",
                        'is_default' => true,
                    ]
                );
            }
        });
    }
}
