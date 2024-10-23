<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseTypeController extends Controller
{
    protected CourseService $courseService;
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    public function showCourse(int $id): JsonResponse
    {
        $course = $this->courseService->getCourseByIdWithAccessControl($id);
        $user = Auth::check() ? Auth::user() : null;
        $userHasAccess = $this->courseService->checkUserAccessStatus($user, $id);
        $lessons = $this->courseService->getLessonsForCourseWithAccess($id);
        return response()->json([
            'authenticated' => (bool)$user,
            'course' => $course,
            'userHasAccess' => $userHasAccess,
            'lessons' => $lessons,
        ]);
    }
}
