<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UserResource;
use App\Models\Course;
use App\Models\Lessons;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            $courses = Course::where('teacher_id', $user->id)->get();
        } else {
            $courses = Course::paginate(20);
        }

        return response()->json($courses);
    }

    public function show($id): JsonResponse
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::with('lessons')->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json([
            'course' => $course,
            'lessons' => $course->lessons
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'lessons' => 'required|array',
            'lessons.*.title' => 'required|string|max:255',
            'lessons.*.video_url' => 'required|file|mimetypes:video/mp4,video/x-m4v,video/*',
            'lessons.*.markdown_text' => 'required|string',
        ]);

        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        $user = Auth::user();

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'type' => $request->type,
            'teacher_id' => $user->id,
        ]);

        foreach ($request->lessons as $lesson) {
            $videoPath = $lesson['video_url']->store('videos', 'public');

            Lessons::create([
                'course_id' => $course->id,
                'title' => $lesson['title'],
                'video_url' => $videoPath,
                'markdown_text' => $lesson['markdown_text'],
            ]);
        }

        return response()->json(['create course' => $course], 201);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $lessons = $course->lessons;
        foreach ($lessons as $lesson) {
            if (Storage::disk('public')->exists($lesson->video_url)) {
                Storage::disk('public')->delete($lesson->video_url);
            }
            $lesson->delete();
        }

        if (Storage::disk('public')->exists($course->thumbnail)) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return response()->json(['message' => 'Course and associated lessons deleted successfully.']);
    }
}


