<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Engagement;
use App\Models\Lessons;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

//        $user = Auth::check() ? Auth::user() : null;

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
            'currentUser' => Auth::user(),
        ]);
    }



    public function storeComment(Request $request, Course $course, Lessons $lesson): JsonResponse
    {
        // Validate the comment input
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Check if the user has access to the course
        $user = Auth::user();

//        if (!$course->isAvailableToUser($user)) {
//            return response()->json(['error' => 'You do not have access to this course.'], 403);
//        }

        // Create the comment
        $comment = Comment::create([
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
            'comment' => $validated['comment'],
        ]);

        return response()->json(['message' => 'Comment added successfully!', 'comment' => $comment], 201);
    }

    public function profilePage($id): JsonResponse
    {
        Log::info('User ID passed to profilePage:', ['id' => $id]);
        $currentUser = Auth::user();

        $user = User::with([
            'achievements' => function ($query) {
                $query->with('course'); // Eager load course data for achievements
            },
            'purchasedCourses' => function ($query) {
                $query->with('teacher'); // Eager load teacher data for purchased courses
            },
            'engagements' => function ($query) {
                $query->with('course');
            },
            'comments' => function ($query) use ($id) { // Filter comments by user ID
                $query->where('user_id', $id)
                    ->with(['lesson' => function ($lessonQuery) {
                        $lessonQuery->latest()->take(5);
                    }, 'likes']); // Eager load lesson data and likes for comments
            },
        ])->findOrFail($id);

        $user->setHidden(['email', 'password', 'age', 'email_verified_at', 'remember_token']);

        foreach ($user->comments as $comment) {
            if ($comment->lesson) {
                $comment->lesson->setHidden(['video_url', 'markdown_text']);
            }
        }

        $commentCount = $user->comments()->count();
        $purchasedCoursesCount = $user->purchasedCourses()->count();

        $registrationDate = $user->created_at->timezone('UTC');
        $currentDate = Carbon::now('UTC');

        // Calculate the difference in days and ensure it's positive
        $daysSinceRegistration = abs($currentDate->diffInDays($registrationDate));

        // For users with the "teacher" role, count the number of created courses
        $createdCoursesCount = 0;
        if ($user->role === 'teacher') {
            $createdCoursesCount = Course::where('teacher_id', $user->id)->count();
        }

        $statistics = [
            'commentCount' => $commentCount,
            'completedLessonsCount' => $user->courses()->wherePivot('completed', true)->count(),
            'purchasedCoursesCount' => $purchasedCoursesCount,
            'registrationDate' => $registrationDate->format('Y-m-d'),
            'daysSinceRegistration' => $daysSinceRegistration, // This should be an integer
            'createdCoursesCount' => $user->courses()->count(),
        ];

        return response()->json([
            'user' => $user,
            'authenticated' => (bool) $currentUser,
            'currentUser' => $currentUser ?: null,
            'statistics' => $statistics,
        ]);
    }


    public function showBookmark(Request $request)
    {
        $user = $request->user();
        return response()->json($user->bookmarks);
    }

    // Add a course to bookmarks
    public function storeBookmark(Request $request, Course $course)
    {
        $user = $request->user();
        if (!$user->hasBookmarkedCourse($course)) {
            $user->bookmarks()->attach($course);
        }

        return response()->json(['message' => 'Course added to bookmarks']);
    }

    // Remove a course from bookmarks
    public function destroyBookmark(Request $request, Course $course)
    {
        $user = $request->user();
        if ($user->hasBookmarkedCourse($course)) {
            $user->bookmarks()->detach($course);
        }

        return response()->json(['message' => 'Course removed from bookmarks']);
    }


    public function getUserActivities(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        $engagements = $currentUser->engagements;

        // Initialize an array to store the interaction counts per month
        $monthlyInteractions = [];

        foreach ($engagements as $engagement) {
            foreach ($engagement->interactions as $interaction) {
                // Get the month and year from the interaction timestamp
                $month = \Carbon\Carbon::parse($interaction['timestamp'])->format('Y-m');

                // Increment the count for the corresponding month
                if (!isset($monthlyInteractions[$month])) {
                    $monthlyInteractions[$month] = 0;
                }
                $monthlyInteractions[$month]++;
            }
        }

        // Return the interaction count per month as JSON
        return response()->json($monthlyInteractions);
    }



}
