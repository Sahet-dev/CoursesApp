<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourseService
{

    public function getLatestCoursesWithAccessControl($limit = 4)
    {
        // Fetch the latest courses
        $courses = Course::with('lessons')->latest()->take($limit)->get();

        // Check if the user is authenticated
        $user = Auth::user();

        // Map courses with appropriate lesson data based on user access
        return $courses->map(function ($course) use ($user) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'price' => $course->price,
                'thumbnail' => $course->thumbnail,
                'lessons' => $this->getCourseLessonsWithAccessControl($course, $user),
            ];
        });
    }


    // Method to get the most popular courses
    public function getMostPopularCourses($limit = 4)
    {
        return Course::withCount('engagements') // Assuming engagements represent popularity
        ->orderBy('engagements_count', 'desc')
            ->take($limit)
            ->get();
    }

    // Method to get course lessons with access control
    public function getCourseLessonsWithAccessControl(Course $course, ?User $user)
    {
        // Eager load lessons with questions
        $course->load('lessons.questions');

        // Check if the user is authenticated and has access to the full course content
        if ($user && ($course->isAvailableToUser($user) || $course->isPurchasedBy($user))) {
            return $course->lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'video_url' => $lesson->video_url,
                    'markdown_text' => $lesson->markdown_text,
                    'questions' => $lesson->questions->map(function ($question) {
                        return [
                            'id' => $question->id,
                            'question_text' => $question->question_text,
                        ];
                    }),
                ];
            });
        }

        // For unauthenticated users or users without a subscription or purchase, return only lesson titles
        return $course->lessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'title' => $lesson->title,
                // Do not include 'video_url', 'markdown_text', or 'questions'
            ];
        });
    }

}

