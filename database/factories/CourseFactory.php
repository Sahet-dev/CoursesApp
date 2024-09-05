<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'thumbnail' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'teacher_id' => \App\Models\User::factory(), // Assuming a course has a teacher
            'premium' => $this->faker->boolean,
            'subscription_access' => $this->faker->boolean,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Course $course) {
            // Create some lessons for this course
            \App\Models\Lessons::factory()->count(5)->create(['course_id' => $course->id]);
        });
    }
}
