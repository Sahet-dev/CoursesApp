<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Course::create([
            'title' => 'Officia labore aut quibusdam qui.',
            'description' => 'Amet minima doloribus voluptatem voluptate quos expedita nostrum.',
            'thumbnail' => 'https://picsum.photos/id/237/200/300',
            'teacher_id' => 1,
            'premium' => 1,
            'subscription_access' => 0,
            // Remove 'price' if it no longer exists
        ]);
    }
}
