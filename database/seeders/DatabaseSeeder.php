<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'ammarabdulaziz99@gmail.com',
        ],[
            'name' => 'Ammar Abdul Aziz',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate([
            'email' => 'test@gmail.com',
        ],[
            'name' => 'Ammar Abdul Aziz',
            'password' => Hash::make('Test@123'),
            'email_verified_at' => now(),
        ]);

        // Seed all AssetForm related data
        $this->call([
            AccountTypeSeeder::class,
            FriendSeeder::class,
            InvestmentTypeSeeder::class,
            DepositTypeSeeder::class,
        ]);
    }
}
