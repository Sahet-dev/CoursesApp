<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendCourseController extends Controller
{
    protected CourseService $courseService;
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function getCourses(Request $request)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = auth()->user();

            // Fetch popular and latest courses
            $popularCourses = $this->courseService->getMostPopularCourses(4);
            $latestCourses = $this->courseService->getLatestCoursesWithAccessControl(4);

            // Map courses with lessons
            $coursesWithLessons = $popularCourses->map(function ($course) use ($user) {
                return [
                    'id' => $course['id'], // Assuming $course is an array
                    'title' => $course['title'], // Assuming $course is an array
                    'lessons' => $this->courseService->getCourseLessonsWithAccessControl(Course::find($course['id']), $user), // Use the method to get lessons
                ];
            });

            $latestCoursesWithLessons = $latestCourses->map(function ($course) use ($user) {
                return [
                    'id' => $course['id'], // Assuming $course is an array
                    'title' => $course['title'], // Assuming $course is an array
                    'lessons' => $this->courseService->getCourseLessonsWithAccessControl(Course::find($course['id']), $user), // Use the method to get lessons
                ];
            });

            // Return JSON response with user data and courses
            return response()->json([
                'popularCourses' => $coursesWithLessons,
                'latestCourses' => $latestCoursesWithLessons,
                'user' => $user
            ]);
        } else {
            // Fetch popular and latest courses
            $popularCourses = $this->courseService->getMostPopularCourses(4);
            $latestCourses = $this->courseService->getLatestCoursesWithAccessControl(4);

            // Return JSON response for guest users
            return response()->json([
                'popularCourses' => $popularCourses,
                'latestCourses' => $latestCourses,
            ]);
        }
    }



    public function showCourse(int $id): JsonResponse
    {
        // Fetch the course with access control using the existing service
        $course = $this->courseService->getCourseByIdWithAccessControl($id);

        $user = Auth::check() ? Auth::user() : null;

        // Determine if the user has access to all course content
        $userHasAccess = $this->courseService->checkUserAccessStatus($user, $id);

        // Fetch lessons based on access control without modifying the current method
        $lessons = $this->courseService->getLessonsForCourseWithAccess($id);

        // Return JSON response
        return response()->json([
            'authenticated' => (bool)$user,
            'course' => $course,
            'user' => $user,
            'userHasAccess' => $userHasAccess, // Pass the access status
            'lessons' => $lessons,
        ]);
    }


}
