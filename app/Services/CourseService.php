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
        $courses = Course::with('questions')->latest()->take($limit)->get();

        $user = Auth::user();

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
        $course = Course::with('questions')->findOrFail($id);

        $user = Auth::user();

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
        $course->load([
            'questions',
            'lessons.comments.user',
            'lessons.comments.likes',
            'lessons.comments.replies.user',
            'lessons.comments.replies.likes',
        ]);

        if ($user && ($course->isAvailableToUser($user) || $course->isPurchasedBy($user))) {
            return $course->lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'video_url' => $lesson->video_url,
                    'markdown_text' => $lesson->markdown_text,

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

            return $lessons->map(function ($lesson) use ($firstFourLessonsWithDetails) {
                $fullDetails = $firstFourLessonsWithDetails->firstWhere('id', $lesson['id']);
                return array_merge($lesson, $fullDetails ?? []);
            });
        }
    }



    public function checkUserAccessStatus($user, $courseId): bool
    {
        if (!$user) {
            return false;
        }

        $course = Course::find($courseId);
        if (!$course) {
            return false;
        }

        return $course->isAvailableToUser($user) || $course->isPurchasedBy($user);
    }





    public function getLessonsForCourseWithAccess($courseId)
    {
        $course = Course::with('questions')->findOrFail($courseId);

        $user = Auth::user();

        $userHasAccess = $this->checkUserAccessStatus($user, $courseId);

        $commentsWithLikes = $course->lessons->load(['comments.user', 'comments.likes', 'comments.replies.user', 'comments.replies.likes'])
            ->pluck('comments', 'id');

        if ($userHasAccess) {
            return $course->lessons->map(function ($lesson) use ($user, $commentsWithLikes) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'video_url' => $lesson->video_url,
                    'markdown_text' => $lesson->markdown_text,

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
            $lessonsWithLimitedDetails = $course->lessons->take(4)->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'video_url' => $lesson->video_url,
                    'markdown_text' => $lesson->markdown_text,
                ];
            });

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
