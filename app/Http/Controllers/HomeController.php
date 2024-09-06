<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    protected CourseService $courseService;

    // Inject the CourseService in the constructor
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function main(Request $request)
    {
        if (Auth::check()) {
            $user = auth()->user();

            $popularCourses = $this->courseService->getMostPopularCourses(4);
            $latestCourses = $this->courseService->getLatestCoursesWithAccessControl(4);

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

            return Inertia::render('components/UserPage', [
                'popularCourses' => $coursesWithLessons,
                'latestCourses' => $latestCoursesWithLessons,
                'user' => $user
            ]);
        } else {
            $popularCourses = $this->courseService->getMostPopularCourses(4);
            $latestCourses = $this->courseService->getLatestCoursesWithAccessControl(4);

            return Inertia::render('components/GuestPage', [
                'popularCourses' => $popularCourses,
                'latestCourses' => $latestCourses,
            ]);
        }
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');

        // Fields to select
        $fields = ['id', 'title', 'description', 'thumbnail', 'price'];

        // Query to fetch all courses (both general and premium)
        $coursesQuery = Course::select($fields);

        // Apply search filter if provided
        if ($search) {
            $coursesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Get all courses
        $courses = $coursesQuery->get();

        // Render the view with the courses
        return Inertia::render('components/CourseCatalog', [
            'courses' => $courses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }



    public function search(Request $request)
    {
        // Get the search term from the request
        $search = $request->input('search');

        // Fetch courses, filtering by the search term if provided
        $courses = Course::when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })->get();

        // Pass the courses and search term to the frontend via Inertia
        return Inertia::render('components/CourseCatalog', [
            'courses' => $courses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }



    public function show(int $id): Response
    {
        // Fetch the course with access control using the existing service
        $course = $this->courseService->getCourseByIdWithAccessControl($id); // This remains unchanged

        // Get the authenticated user

        $user = Auth::check() ? Auth::user() : null;


        // Determine if the user has access to all course content
        $userHasAccess = $this->courseService->checkUserAccessStatus($user, $id);

        // Fetch lessons based on access control without modifying the current method
        $lessons = $this->courseService->getLessonsForCourseWithAccess($id);

        // Pass the course data and access information to the Inertia component
        return Inertia::render('CourseDetail', [
            'course' => $course,
            'user' => $user,
            'userHasAccess' => $userHasAccess, // Pass the access status
            'lessons' => $lessons,
        ]);
    }
















}
