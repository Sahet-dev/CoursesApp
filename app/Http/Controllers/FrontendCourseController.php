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
        $user = auth()->user();

        $popularCourses = Course::getPopularCourses();
        $latestCourses = Course::getLatestCourses();

        return response()->json([
            'popularCourses' => $popularCourses,
            'latestCourses' => $latestCourses,
            'user' => $user,
        ]);

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

    public function getUserLatestActivities(Request $request): JsonResponse
    {
        $currentUser = $request->user();

        $latestCourses = DB::table('interactions')
            ->join('engagements', 'interactions.engagement_id', '=', 'engagements.id')
            ->join('courses', 'engagements.course_id', '=', 'courses.id')
            ->where('engagements.user_id', $currentUser->id)
            ->orderBy('interactions.timestamp', 'desc')
            ->select('courses.id', 'courses.title', 'courses.description', 'courses.thumbnail', 'courses.price', 'courses.type')
            ->distinct()
            ->take(2)
            ->get();


        $popularCourses = DB::table('interactions')
            ->join('engagements', 'interactions.engagement_id', '=', 'engagements.id')
            ->join('courses', 'engagements.course_id', '=', 'courses.id')
            ->select(
                'courses.id',
                'courses.title',
                'courses.description',
                'courses.thumbnail',
                'courses.price',
                'courses.type',
                DB::raw('COUNT(interactions.id) as interaction_count')
            )
            ->groupBy(
                'courses.id',
                'courses.title',
                'courses.description',
                'courses.thumbnail',
                'courses.price',
                'courses.type'
            )
            ->orderBy('interaction_count', 'desc')
            ->take(4)
            ->get();

        return response()->json([
            'latestCourses' => $latestCourses,
            'popularCourses' => $popularCourses
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

    public function getUserActivities(Request $request): JsonResponse
    {
        $currentUser = $request->user();

        $monthlyInteractions = DB::table('interactions')
            ->join('engagements', 'interactions.engagement_id', '=', 'engagements.id')
            ->where('engagements.user_id', $currentUser->id)
            ->select(
                DB::raw("DATE_FORMAT(interactions.timestamp, '%Y-%m') as month"),
                DB::raw('COUNT(interactions.id) as interaction_count')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json($monthlyInteractions);
    }
}

