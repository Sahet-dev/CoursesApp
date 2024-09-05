<?php

namespace Database\Seeders;

use App\Models\Lessons;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = \App\Models\Course::all();

        // Create lessons for each course
        foreach ($courses as $course) {
            Lessons::factory()
                ->count(5) // Adjust the count as needed
                ->create(['course_id' => $course->id]);
        }
    }
}
