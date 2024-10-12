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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FrontendCourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function getCourses(Request $request): JsonResponse
    {
        if (Auth::check()) {
            $user = auth()->user();

            $popularCourses = $this->courseService->getMostPopularCourses(4);
            $latestCourses = $this->courseService->getLatestCoursesWithAccessControl(4);

            $coursesWithLessons = $popularCourses->map(function ($course) use ($user) {
                return [
                    'id' => $course['id'],
                    'title' => $course['title'],
                    'lessons' => $this->courseService->getCourseLessonsWithAccessControl(Course::find($course['id']), $user),
                ];
            });

            $latestCoursesWithLessons = $latestCourses->map(function ($course) use ($user) {
                return [
                    'id' => $course['id'],
                    'title' => $course['title'],
                    'lessons' => $this->courseService->getCourseLessonsWithAccessControl(Course::find($course['id']), $user),
                ];
            });

            return response()->json([
                'popularCourses' => $coursesWithLessons,
                'latestCourses' => $latestCoursesWithLessons,
                'user' => $user,
            ]);
        } else {
            $popularCourses = $this->courseService->getMostPopularCourses(4);
            $latestCourses = $this->courseService->getLatestCoursesWithAccessControl(4);

            return response()->json([
                'popularCourses' => $popularCourses,
                'latestCourses' => $latestCourses,
            ]);
        }
    }

    public function storeComment(Request $request, Course $course, Lessons $lesson): JsonResponse
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

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
                $query->with('course');
            },
            'purchasedCourses' => function ($query) {
                $query->with('teacher');
            },
            'engagements' => function ($query) {
                $query->with('course');
            },
            'comments' => function ($query) use ($id) {
                $query->where('user_id', $id)
                    ->with(['lesson' => function ($lessonQuery) {
                        $lessonQuery->latest()->take(5);
                    }, 'likes']);
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

        $daysSinceRegistration = abs($currentDate->diffInDays($registrationDate));

        $createdCoursesCount = 0;
        if ($user->role === 'teacher') {
            $createdCoursesCount = Course::where('teacher_id', $user->id)->count();
        }

        $statistics = [
            'commentCount' => $commentCount,
            'completedLessonsCount' => $user->courses()->wherePivot('completed', true)->count(),
            'purchasedCoursesCount' => $purchasedCoursesCount,
            'registrationDate' => $registrationDate->format('Y-m-d'),
            'daysSinceRegistration' => $daysSinceRegistration,
            'createdCoursesCount' => $user->courses()->count(),
        ];

        return response()->json([
            'user' => $user,
            'authenticated' => (bool) $currentUser,
            'currentUser' => $currentUser ?: null,
            'statistics' => $statistics,
        ]);
    }

    public function showBookmark(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json($user->bookmarks);
    }

    public function storeBookmark(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();
        if (!$user->hasBookmarkedCourse($course)) {
            $user->bookmarks()->attach($course);
        }

        return response()->json(['message' => 'Course added to bookmarks']);
    }

    public function destroyBookmark(Request $request, Course $course): JsonResponse
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

        $monthlyInteractions = [];

        foreach ($engagements as $engagement) {
            foreach ($engagement->interactions as $interaction) {
                $month = \Carbon\Carbon::parse($interaction['timestamp'])->format('Y-m');

                if (!isset($monthlyInteractions[$month])) {
                    $monthlyInteractions[$month] = 0;
                }
                $monthlyInteractions[$month]++;
            }
        }

        return response()->json($monthlyInteractions);
    }

    public function getUserLatestActivities(Request $request): JsonResponse
    {
        $currentUser = $request->user();

        $mostInteractedCourses = Engagement::where('user_id', $currentUser->id)
            ->where('completed', 0)
            ->select('course_id', DB::raw('COUNT(interactions) as interaction_count'))
            ->groupBy('course_id')
            ->orderBy('interaction_count', 'desc')
            ->take(2)
            ->with('course:id,title,description,thumbnail')
            ->get();

        return response()->json([
            'most_interacted_courses' => $mostInteractedCourses,
        ]);
    }

    public function courseCatalog(): JsonResponse
    {
        $courses = Course::withBasicDetails()->paginate(10);

        return response()->json($courses);
    }

    public function getCompletedCourses(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        $completedCourses = Engagement::where('user_id', $currentUser->id)
            ->where('completed', 1)
            ->with('course')
            ->get()
            ->pluck('course');

        return response()->json($completedCourses);
    }
}

