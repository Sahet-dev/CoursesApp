<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Engagement;
use App\Models\Lessons;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $user = auth()->user();
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
            'authenticated' => (bool)$user,
            'user' => $user ?: null,
            'courses' => $courses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }



    public function search(Request $request)
    {

        $user = auth()->user();

        // Get the search term from the request
        $search = $request->input('search');

        // Fetch courses, filtering by the search term if provided
        $courses = Course::when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })->get();

        // Pass the courses and search term to the frontend via Inertia
        return Inertia::render('components/CourseCatalog', [
            'authenticated' => (bool)$user,
            'user' => $user ?: null,
            'courses' => $courses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }



    public function show(int $id): Response
    {
        // Fetch the course with access control using the existing service
        $course = $this->courseService->getCourseByIdWithAccessControl($id);


        $user = Auth::check() ? Auth::user() : null;


        // Determine if the user has access to all course content
        $userHasAccess = $this->courseService->checkUserAccessStatus($user, $id);

        // Fetch lessons based on access control without modifying the current method
        $lessons = $this->courseService->getLessonsForCourseWithAccess($id);

        // Pass the course data and access information to the Inertia component
        return Inertia::render('CourseDetail', [
            'authenticated' => (bool)$user,

            'course' => $course,
            'user' => $user,
            'userHasAccess' => $userHasAccess, // Pass the access status
            'lessons' => $lessons,
        ]);
    }




    public function toggleLike(int $courseId, int $lessonId, int $commentId)
    {
        $user = Auth::user();

        // Ensure user is authenticated
        if (!$user) {
            return Inertia::render('Error', ['message' => 'User not authenticated'])->with('error', 'User not authenticated');
        }

        $comment = Comment::find($commentId);

        if (!$comment) {
            return Inertia::render('Error', ['message' => 'Comment not found'])->with('error', 'Comment not found');
        }

        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Unlike the comment
            $like->delete();
            $message = 'Unliked';
        } else {
            // Like the comment
            $comment->likes()->create(['user_id' => $user->id]);
            $message = 'Liked';
        }

        // Fetch the updated lesson and comments (only necessary data)
        $lesson = Lessons::with(['comments' => function ($query) {
            $query->withCount('likes'); // Only fetch the like count
        }])->find($lessonId);

        return Inertia::render('CourseDetail', [
            'selectedLesson' => $lesson,
            'message' => $message,
        ]);
    }



    public function getCommentsForLesson($lessonId)
    {
        $comments = Comment::with(['user', 'likes', 'replies.user', 'replies.likes'])
            ->where('lesson_id', $lessonId)
            ->get();

        // Map comments to include required information
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


    public function createComment(Request $request, Course $course, Lessons $lesson)
    {
        Log::info('Comment added successfully!');
        $request->validate([
            'comment' => 'required|string|max:955',
        ]);

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->lesson_id = $lesson->id;
        $comment->comment = $request->input('comment');
        $comment->save();
        Log::info($comment);

        return redirect()->back()->with('message', 'Comment added successfully!');

    }


    public function saveLessonTime(Request $request, $courseId, $lessonId)
    {
        $user = Auth::user();
        $timeSpent = $request->input('time_spent');

        // Ensure the course and lesson exist
        $course = Course::findOrFail($courseId);
        $lesson = Lessons::where('id', $lessonId)->where('course_id', $courseId)->firstOrFail();

        // Update or create an engagement entry for the user, course, and lesson
        $engagement = Engagement::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson->id, // Include lesson_id
            ],
            [
                'time_spent' => DB::raw('time_spent + ' . (int) $timeSpent), // Accumulate time spent
            ]
        );

        return back()->with('success', 'Time spent on lesson saved successfully.');
    }


    public function storeInteractions()
    {
        //
    }







}
