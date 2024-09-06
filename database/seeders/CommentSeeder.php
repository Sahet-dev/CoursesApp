<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\User;
use App\Models\Lessons;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all(); // Get all users
        $lessons = Lessons::all(); // Get all lessons

        // Ensure there are users and lessons in the database
        if ($users->isEmpty() || $lessons->isEmpty()) {
            $this->command->info('No users or lessons found. Please seed users and lessons first.');
            return;
        }

        foreach ($lessons as $lesson) {
            foreach ($users->random(3) as $user) {
                Comment::create([
                    'lesson_id' => $lesson->id,
                    'user_id' => $user->id,
                    'comment' => $this->generateRandomContent(),
                ]);
            }
        }
    }

    /**
     * Generate random content for the comments.
     *
     * @return string
     */
    private function generateRandomContent()
    {
        return Str::random(100); // Adjust the length as needed
    }
}
