<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Http\Resources\LessonResource;
use App\Models\Course;
use App\Models\Lessons;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'video_url' => 'nullable|mimes:mp4,mov,avi,mpg,wmv|max:10000',
            'markdown_text' => 'nullable|string',
        ]);

        $lesson = new Lessons();
        $lesson->course_id = $course->id;
        $lesson->title = $request->input('title', 'Default Title');
        $lesson->markdown_text = $request->input('markdown_text', 'Default Markdown Text');

        if ($request->hasFile('video_url')) {
            $lesson->video_url = $request->file('video_url')->store('videos', 'public');
        } else {
            $lesson->video_url = 'default_video.mp4';
        }

        $course->lessons()->save($lesson);
        return response()->json(['lesson' => $lesson], 201);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            $lesson = Lessons::where('id', $id)
                ->whereHas('course', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                })
                ->firstOrFail();
        } else {
            $lesson = Lessons::findOrFail($id);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'markdown_text' => 'nullable|string',
            'video_url' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480',
        ]);

        $lesson->update($validatedData);

        if ($request->hasFile('video_url')) {
            $videoPath = $request->file('video_url')->store('videos', 'public');
            $lesson->update(['video_url' => $videoPath]);
        }

        return response()->json(['message' => 'Lesson updated successfully', 'lesson' => $lesson]);
    }

    public function fetchLessons($id)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::with('lessons')->findOrFail($id);
        return response()->json(['lessons' => $course->lessons]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|integer|exists:lessons,id',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.responses' => 'required|array',
            'questions.*.responses.*.response_text' => 'required|string',
            'questions.*.responses.*.is_correct' => 'required|boolean',
        ]);

        $lessonId = $validated['lesson_id'];
        $questions = $validated['questions'];

        foreach ($questions as $questionData) {
            $question = Question::create([
                'lesson_id' => $lessonId,
                'question_text' => $questionData['question_text'],
            ]);

            foreach ($questionData['responses'] as $responseData) {
                $question->responses()->create([
                    'response_text' => $responseData['response_text'],
                    'is_correct' => $responseData['is_correct'],
                ]);
            }
        }

        return response()->json(['message' => 'Questions added successfully!'], 201);
    }

    public function updateQuestionsForCourse(Request $request, $courseId)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($user->hasRole('teacher') && $course->teacher_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'nullable|integer|exists:questions,id',
            'questions.*.question_text' => 'required|string',
            'questions.*.responses' => 'required|array',
            'questions.*.responses.*.id' => 'nullable|integer|exists:responses,id',
            'questions.*.responses.*.response_text' => 'required|string',
            'questions.*.responses.*.is_correct' => 'required|boolean',
        ]);

        foreach ($validated['questions'] as $questionData) {
            if (isset($questionData['id'])) {
                $question = Question::findOrFail($questionData['id']);

                if ($question->course_id !== $course->id) {
                    return response()->json(['message' => 'Unauthorized: Question does not belong to this course'], 403);
                }

                $question->update([
                    'question_text' => $questionData['question_text'],
                ]);

                foreach ($questionData['responses'] as $responseData) {
                    if (isset($responseData['id'])) {
                        $response = Response::findOrFail($responseData['id']);
                        $response->update([
                            'response_text' => $responseData['response_text'],
                            'is_correct' => $responseData['is_correct'],
                        ]);
                    } else {
                        $question->responses()->create([
                            'response_text' => $responseData['response_text'],
                            'is_correct' => $responseData['is_correct'],
                        ]);
                    }
                }
            } else {
                $question = $course->questions()->create([
                    'question_text' => $questionData['question_text'],
                ]);

                foreach ($questionData['responses'] as $responseData) {
                    $question->responses()->create([
                        'response_text' => $responseData['response_text'],
                        'is_correct' => $responseData['is_correct'],
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Questions and responses updated successfully!'], 200);
    }

    public function showTest($lessonId)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            $questions = Question::where('lesson_id', $lessonId)
                ->whereHas('lesson', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                })
                ->get();
        } else {
            $questions = Question::where('lesson_id', $lessonId)->get();
        }

        return response()->json(['questions' => $questions], 200);
    }

    public function getQuestionsByCourse($courseId)
    {
        $user = Auth::user();

        if (!$user->hasRole('teacher')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::where('id', $courseId)
            ->where('teacher_id', $user->id)
            ->with('questions.responses')
            ->first();

        if (!$course) {
            return response()->json(['message' => 'Course not found or unauthorized.'], 404);
        }

        $questions = $course->questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'responses' => $question->responses->map(function ($response) {
                    return [
                        'id' => $response->id,
                        'response_text' => $response->response_text,
                        'is_correct' => $response->is_correct,
                    ];
                }),
            ];
        });

        return response()->json(['questions' => $questions], 200);
    }
}
