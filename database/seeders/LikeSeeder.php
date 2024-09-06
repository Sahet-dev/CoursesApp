<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\User;
use App\Models\Comment;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch all user IDs from the database
        $userIds = User::pluck('id')->toArray();

        // Loop through comment IDs from 1 to 300
        foreach (range(1, 300) as $commentId) {
            // Randomize the number of likes for each comment (e.g., between 0 and 10)
            $likesCount = rand(0, 10);

            // Shuffle user IDs to ensure random selection and avoid duplicates
            $shuffledUserIds = collect($userIds)->shuffle()->take($likesCount);

            foreach ($shuffledUserIds as $userId) {
                // Create a like for the comment by the selected user
                Like::firstOrCreate([
                    'comment_id' => $commentId,
                    'user_id' => $userId,
                ]);
            }
        }
    }
}
