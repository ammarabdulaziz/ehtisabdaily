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
        $users = User::all();
        
        foreach ($users as $user) {
            DepositType::create([
                'user_id' => $user->id,
                'name' => 'Bank Fixed Deposit',
                'description' => 'Bank fixed deposit accounts',
                'is_default' => true,
            ]);
            
            DepositType::create([
                'user_id' => $user->id,
                'name' => 'Security Deposit',
                'description' => 'Security deposits for rentals, etc.',
                'is_default' => true,
            ]);
            
            DepositType::create([
                'user_id' => $user->id,
                'name' => 'Government Bonds',
                'description' => 'Government bond investments',
                'is_default' => true,
            ]);
        }
    }
}
