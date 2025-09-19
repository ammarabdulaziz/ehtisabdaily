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
        $users = User::all();
        
        foreach ($users as $user) {
            InvestmentType::create([
                'user_id' => $user->id,
                'name' => 'Stocks',
                'description' => 'Stock market investments',
                'is_default' => true,
            ]);
            
            InvestmentType::create([
                'user_id' => $user->id,
                'name' => 'Mutual Funds',
                'description' => 'Mutual fund investments',
                'is_default' => true,
            ]);
            
            InvestmentType::create([
                'user_id' => $user->id,
                'name' => 'Real Estate',
                'description' => 'Real estate investments',
                'is_default' => true,
            ]);
        }
    }
}
