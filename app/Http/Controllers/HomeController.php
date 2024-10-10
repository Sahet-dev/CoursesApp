<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Engagement;
use App\Models\Lessons;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function main(Request $request)
    {
        $user = Auth::check() ? auth()->user() : null;

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

        if ($user) {
            return Inertia::render('components/UserPage', [
                'popularCourses' => $coursesWithLessons,
                'latestCourses' => $latestCoursesWithLessons,
                'user' => $user,
            ]);
        }

        return Inertia::render('components/GuestPage', [
            'popularCourses' => $popularCourses,
            'latestCourses' => $latestCourses,
        ]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search', '');

        $fields = ['id', 'title', 'description', 'thumbnail'];

        $coursesQuery = Course::select($fields);

        if ($search) {
            $coursesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $coursesQuery->get();

        return Inertia::render('components/CourseCatalog', [
            'authenticated' => (bool)$user,
            'user' => $user ?: null,
            'courses' => $courses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $courses = Course::when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })->get();

        return response()->json([
            'courses' => $courses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function show(int $id): Response
    {
        $course = $this->courseService->getCourseByIdWithAccessControl($id);
        $user = Auth::check() ? Auth::user() : null;

        $userHasAccess = $this->courseService->checkUserAccessStatus($user, $id);
        $lessons = $this->courseService->getLessonsForCourseWithAccess($id);

        return Inertia::render('CourseDetail', [
            'authenticated' => (bool)$user,
            'course' => $course,
            'user' => $user,
            'userHasAccess' => $userHasAccess,
            'lessons' => $lessons,
        ]);
    }

    public function toggleLike(int $courseId, int $lessonId, int $commentId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $message = 'Unliked';
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $message = 'Liked';
        }

        $lesson = Lessons::with(['comments' => function ($query) {
            $query->withCount('likes');
        }])->find($lessonId);

        return response()->json([
            'message' => $message,
            'selectedLesson' => $lesson,
            'commentLikesCount' => $comment->likes()->count(),
        ], 200);
    }

    public function getCommentsForLesson($lessonId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $comments = Comment::with(['user', 'likes', 'replies.user', 'replies.likes'])
            ->where('lesson_id', $lessonId)
            ->get();

        return $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'avatar' => $comment->user->avatar,
                ],
                'comment' => $comment->comment,
                'likes_count' => $comment->likes->count(),
                'liked_by_user' => $comment->likes->contains('user_id', Auth::id()),
                'replies' => $comment->replies->map(function ($reply) {
                    return [
                        'id' => $reply->id,
                        'user' => [
                            'id' => $reply->user->id,
                            'name' => $reply->user->name,
                        ],
                        'comment' => $reply->comment,
                        'likes_count' => $reply->likes->count(),
                        'liked_by_user' => $reply->likes->contains('user_id', Auth::id()),
                    ];
                }),
            ];
        });
    }

    public function getComments($lessonId)
    {
        $comments = Comment::with(['user', 'likes', 'replies.user', 'replies.likes'])
            ->where('lesson_id', $lessonId)
            ->get();

        return $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'avatar' => $comment->user->avatar,
                ],
                'comment' => $comment->comment,
                'likes_count' => $comment->likes->count(),
            ];
        });
    }

    public function createComment(Request $request, Course $course, Lessons $lesson)
    {
        $request->validate([
            'comment' => 'required|string|max:955',
        ]);

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->lesson_id = $lesson->id;
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->back()->with('message', 'Comment added successfully!');
    }

    public function saveLessonTime(Request $request, $courseId, $lessonId)
    {
        $user = Auth::user();
        $timeSpent = $request->input('time_spent');

        $course = Course::findOrFail($courseId);
        $lesson = Lessons::where('id', $lessonId)->where('course_id', $courseId)->firstOrFail();

        $engagement = Engagement::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'time_spent' => DB::raw('time_spent + ' . (int)$timeSpent),
            ]
        );

        return back()->with('success', 'Time spent on lesson saved successfully.');
    }

    public function storeInteractions(Request $request, $courseId)
    {
        $user = auth()->user();

        $engagement = Engagement::firstOrCreate(
            ['course_id' => $courseId, 'user_id' => $user->id],
            ['lesson_id' => $request->lesson_id]
        );

        $engagement->addInteraction(
            $request->interaction_type,
            $request->lesson_id,
            now()->toDateTimeString()
        );

        return response()->json(['message' => 'Interaction stored successfully'], 200);
    }
}
