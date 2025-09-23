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
        $user = User::first();
        if (!$user) {
            $this->command->error('No user found. Please run UserSeeder first.');
            return;
        }

        // Create friends from SQL dump
        $friendsData = [
            ['id' => 1, 'name' => 'Bilal Abdul Aziz', 'description' => null, 'is_default' => false],
            ['id' => 2, 'name' => 'Muhsin MOPH', 'description' => null, 'is_default' => false],
            ['id' => 3, 'name' => 'Yaseen Muhammed', 'description' => null, 'is_default' => false],
            ['id' => 4, 'name' => 'Omar Bin Abdul Aziz', 'description' => null, 'is_default' => false],
            ['id' => 5, 'name' => 'Muhammed Faheem', 'description' => null, 'is_default' => false],
            ['id' => 6, 'name' => 'Shafeeq Sias', 'description' => null, 'is_default' => false],
            ['id' => 7, 'name' => 'Fayiz Regency', 'description' => null, 'is_default' => false],
            ['id' => 8, 'name' => 'Manu', 'description' => null, 'is_default' => false],
            ['id' => 9, 'name' => 'Manikandan', 'description' => null, 'is_default' => false],
            ['id' => 10, 'name' => 'Kunju', 'description' => null, 'is_default' => false],
            ['id' => 11, 'name' => 'Shoukath', 'description' => null, 'is_default' => false],
            ['id' => 12, 'name' => 'Nandakumar', 'description' => null, 'is_default' => false],
            ['id' => 13, 'name' => 'Yousuf Applab', 'description' => null, 'is_default' => false],
            ['id' => 14, 'name' => 'Irfan', 'description' => 'Bilal Friend', 'is_default' => false],
            ['id' => 15, 'name' => 'Bappa', 'description' => null, 'is_default' => false],
            ['id' => 16, 'name' => 'Azhar', 'description' => null, 'is_default' => false],
            ['id' => 17, 'name' => 'Ajmal', 'description' => null, 'is_default' => false],
            ['id' => 18, 'name' => 'Khaleel', 'description' => null, 'is_default' => false],
        ];

        foreach ($friendsData as $friendData) {
            Friend::updateOrCreate(
                ['id' => $friendData['id']],
                [
                    'user_id' => $user->id,
                    'name' => $friendData['name'],
                    'description' => $friendData['description'],
                    'is_default' => $friendData['is_default'],
                    'created_at' => '2025-09-22 12:29:04',
                    'updated_at' => '2025-09-22 12:29:04',
                ]
            );
        }
    }
}
