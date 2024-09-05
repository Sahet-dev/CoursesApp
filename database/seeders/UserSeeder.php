<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create an admin user
        User::create([
            'name' => 'AdminUser',
            'email' => 'admin@example.com',
            'password' => Hash::make('password12.'), // Make sure to use a strong password
            'role' => 'admin', // Assuming you have a 'role' column
        ]);

        // Create a moderator user
        User::create([
            'name' => 'ModeratorUser',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password12.'),
            'role' => 'moderator',
        ]);

        // Create a teacher user
        User::create([
            'name' => 'TeacherUser',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password12.'),
            'role' => 'teacher',
        ]);
    }
}
