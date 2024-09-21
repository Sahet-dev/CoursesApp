<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourseService
{
    public function getLatestCoursesWithAccessControl($limit = 4)
    {
        // Fetch the latest courses and their lessons with questions
        $courses = Course::with('questions')->latest()->take($limit)->get();

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

    public function getMostPopularCourses($limit = 4)
    {
        return Course::withCount('engagements') // Assuming engagements represent popularity
        ->orderBy('engagements_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getCourseByIdWithAccessControl($id)
    {
        // Fetch the course by ID with its lessons and questions
        $course = Course::with('questions')->findOrFail($id);

        // Check if the user is authenticated
        $user = Auth::user();

        // Return course data with appropriate lesson data based on user access
        return [
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'thumbnail' => $course->thumbnail,
            'lessons' => $this->getCourseLessonsWithAccessControl($course, $user),
        ];
    }

    public function getCourseLessonsWithAccessControl(Course $course, ?User $user)
    {
        // Eager load lessons with questions, comments, likes, and replies
        $course->load([
            'questions',
            'lessons.comments.user', // Load comments with the user who made them
            'lessons.comments.likes', // Load likes for comments
            'lessons.comments.replies.user', // Load replies with the user who made them
            'lessons.comments.replies.likes', // Load likes for replies
        ]);

        if ($user && ($course->isAvailableToUser($user) || $course->isPurchasedBy($user))) {
            // Authenticated user with access to full content
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
                    'comments' => $lesson->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'avatar' => $comment->avatar,
                            'user' => [
                                'id' => $comment->user->id,
                                'name' => $comment->user->name,
                                'avatar' => $comment->user->avatar,
                            ],
                            'comment' => $comment->comment,
                            'likes_count' => $comment->likes->count(),
                            'replies' => $comment->replies->map(function ($reply) {
                                return [
                                    'id' => $reply->id,
                                    'user' => [
                                        'id' => $reply->user->id,
                                        'name' => $reply->user->name,
                                        'avatar' => $reply->user->avatar,

                                    ],
                                    'comment' => $reply->comment,
                                    'likes_count' => $reply->likes->count(),
                                ];
                            }),
                        ];
                    }),
                ];
            });
        } else {
            // Unauthenticated user or user without subscription
            $lessons = $course->lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                ];
            });

            $firstFourLessonsWithDetails = $course->lessons->take(4)->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'video_url' => $lesson->video_url,
                    'markdown_text' => $lesson->markdown_text,
                ];
            });

            // Merge full details of the first 4 lessons with title-only for the rest
            return $lessons->map(function ($lesson) use ($firstFourLessonsWithDetails) {
                $fullDetails = $firstFourLessonsWithDetails->firstWhere('id', $lesson['id']);
                return array_merge($lesson, $fullDetails ?? []);
            });
        }
    }



    public function checkUserAccessStatus($user, $courseId): bool
    {
        if (!$user) {
            return false; // User is not authenticated
        }

        // Check if the user has an active subscription or has purchased the course
        $course = Course::find($courseId); // Fetch the course
        if (!$course) {
            return false; // Course not found
        }

        return $course->isAvailableToUser($user) || $course->isPurchasedBy($user);
    }





    public function getLessonsForCourseWithAccess($courseId)
    {
        // Fetch the course by ID with its lessons and questions
        $course = Course::with('questions')->findOrFail($courseId);

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has access
        $userHasAccess = $this->checkUserAccessStatus($user, $courseId);

        // Load comments, likes, and replies for all lessons in the course
        $commentsWithLikes = $course->lessons->load(['comments.user', 'comments.likes', 'comments.replies.user', 'comments.replies.likes'])
            ->pluck('comments', 'id');

        // Prepare the lessons data based on user access
        if ($userHasAccess) {
            return $course->lessons->map(function ($lesson) use ($user, $commentsWithLikes) {
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
                    'comments' => $commentsWithLikes->get($lesson->id)->map(function ($comment) use ($user) {
                        return [
                            'id' => $comment->id,
                            'user' => [
                                'id' => $comment->user->id,
                                'name' => $comment->user->name,
                                'avatar' => $comment->user->avatar,
                            ],
                            'comment' => $comment->comment,
                            'likes_count' => $comment->likes->count(),
                            'liked_by_user' => $comment->likes->contains('user_id', $user ? $user->id : null),
                            'replies' => $comment->replies->map(function ($reply) use ($user) {
                                return [
                                    'id' => $reply->id,
                                    'user' => [
                                        'id' => $reply->user->id,
                                        'name' => $reply->user->name,
                                        'avatar' => $reply->user->avatar,
                                    ],
                                    'comment' => $reply->comment,
                                    'likes_count' => $reply->likes->count(),
                                    'liked_by_user' => $reply->likes->contains('user_id', $user ? $user->id : null),
                                ];
                            }),
                        ];
                    }),
                ];
            });
        } else {
            // User does not have access: limit to first 4 lessons with limited details
            $lessonsWithLimitedDetails = $course->lessons->take(4)->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'video_url' => $lesson->video_url,
                    'markdown_text' => $lesson->markdown_text,
                ];
            });

            // Include only titles for the rest of the lessons
            $lessonTitlesOnly = $course->lessons->skip(4)->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                ];
            });

            return $lessonsWithLimitedDetails->merge($lessonTitlesOnly);
        }
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



}
