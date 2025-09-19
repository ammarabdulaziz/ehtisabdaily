<?php

namespace Database\Seeders;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Friend::create([
                'user_id' => $user->id,
                'name' => 'Family Member',
                'description' => 'Default family member entry',
                'is_default' => true,
            ]);
            
            Friend::create([
                'user_id' => $user->id,
                'name' => 'Close Friend',
                'description' => 'Default close friend entry',
                'is_default' => true,
            ]);
        }
    }
}
