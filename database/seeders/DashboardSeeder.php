<?php

namespace Database\Seeders;

use App\Models\Lessons;
use App\Models\User;
use App\Models\Course;
use App\Models\Engagement;
use App\Models\Subscription;
use App\Models\Notifications;
use App\Models\Achievement;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    public function run()
    {
        // Seed users
        $user = User::factory()->create();

        // Seed courses and lessons
        $courses = Course::factory()->count(5)->create();

        foreach ($courses as $course) {
            Lessons::factory()->count(3)->create(['course_id' => $course->id]);
        }

        // Seed engagements
        Engagement::factory()->count(10)->create(['user_id' => $user->id]);

        // Seed subscriptions
        Subscription::factory()->create(['user_id' => $user->id, 'status' => 'active']);

        // Seed notifications
        Notifications::factory()->count(10)->create(['notifiable_id' => $user->id]);

        // Seed achievements and feedback
        Achievement::factory()->count(5)->create(['user_id' => $user->id]);
        Feedback::factory()->count(5)->create();
    }
}
