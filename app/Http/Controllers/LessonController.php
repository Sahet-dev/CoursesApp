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
        // Validate incoming request data
        $request->validate([
            'title' => 'nullable|string|max:255',
            'video_url' => 'nullable|mimes:mp4,mov,avi,mpg,wmv|max:10000',
            'markdown_text' => 'nullable|string',
        ]);

        // Create a new Lesson instance
        $lesson = new Lessons();

        // Assign course_id from the Course instance
        $lesson->course_id = $course->id;

        // Assign other attributes with default values if not provided
        $lesson->title = $request->input('title', 'Default Title');
        $lesson->markdown_text = $request->input('markdown_text', 'Default Markdown Text');

        // Handle video upload
        if ($request->hasFile('video_url')) {
            $lesson->video_url = $request->file('video_url')->store('videos', 'public');
        } else {
            $lesson->video_url = 'default_video.mp4'; // Default video URL if no file is uploaded
        }

        // Save the lesson to the course
        $course->lessons()->save($lesson);

        // Return a JSON response with the created lesson
        return response()->json(['lesson' => $lesson], 201);
    }






    public function update(Request $request, $id)
    {
        // Check if the user has the 'admin', 'moderator', or 'teacher' role
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = Auth::user();

        // Fetch the lesson based on the user role
        if ($user->hasRole('teacher')) {
            // Ensure teachers can only update lessons of the courses they own
            $lesson = Lessons::where('id', $id)
                ->whereHas('course', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                })
                ->firstOrFail();
        } else {
            // Admins and moderators can update any lesson
            $lesson = Lessons::findOrFail($id);
        }

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'markdown_text' => 'nullable|string',
            'video_url' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480',
        ]);

        // Update lesson data
        $lesson->update($validatedData);

        // Handle video file separately if uploaded
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

    // In your Controller handling /tests/create endpoint

    public function create(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|integer|exists:lessons,id', // Ensure lesson_id is valid
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.responses' => 'required|array',
            'questions.*.responses.*.response_text' => 'required|string',
            'questions.*.responses.*.is_correct' => 'required|boolean',
        ]);

        $lessonId = $validated['lesson_id']; // Correctly extract lesson_id
        $questions = $validated['questions'];

        // Loop through each question and save it with responses
        foreach ($questions as $questionData) {
            // Create a question with the associated lesson_id
            $question = Question::create([
                'lesson_id' => $lessonId,  // Make sure lesson_id is included here
                'question_text' => $questionData['question_text'],
            ]);

            // Loop through each response and save it with the question_id
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
        // Check if the user has the 'admin', 'moderator', or 'teacher' role
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = Auth::user();

        // Fetch the course and ensure the authenticated user is the teacher
        $course = Course::findOrFail($courseId);

        // Check if the authenticated user owns the course
        if ($user->hasRole('teacher') && $course->teacher_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'nullable|integer|exists:questions,id',  // Allow for both creating new and updating existing questions
            'questions.*.question_text' => 'required|string',
            'questions.*.responses' => 'required|array',
            'questions.*.responses.*.id' => 'nullable|integer|exists:responses,id',  // Allow for both creating new and updating existing responses
            'questions.*.responses.*.response_text' => 'required|string',
            'questions.*.responses.*.is_correct' => 'required|boolean',
        ]);

        foreach ($validated['questions'] as $questionData) {
            if (isset($questionData['id'])) {
                // Update existing question
                $question = Question::findOrFail($questionData['id']);

                // Ensure the question belongs to the course
                if ($question->course_id !== $course->id) {
                    return response()->json(['message' => 'Unauthorized: Question does not belong to this course'], 403);
                }

                // Update the question text
                $question->update([
                    'question_text' => $questionData['question_text'],
                ]);

                // Update existing responses or create new ones
                foreach ($questionData['responses'] as $responseData) {
                    if (isset($responseData['id'])) {
                        // Update existing response
                        $response = Response::findOrFail($responseData['id']);
                        $response->update([
                            'response_text' => $responseData['response_text'],
                            'is_correct' => $responseData['is_correct'],
                        ]);
                    } else {
                        // Create new response
                        $question->responses()->create([
                            'response_text' => $responseData['response_text'],
                            'is_correct' => $responseData['is_correct'],
                        ]);
                    }
                }
            } else {
                // Create new question and its responses
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
        try {
            // Check if the user has the 'admin', 'moderator', or 'teacher' role
            if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $user = Auth::user();

            // Determine the query for fetching questions based on the user role
            if ($user->hasRole('teacher')) {
                // For teachers, ensure they only access questions for their own lessons
                $questions = Question::where('lesson_id', $lessonId)
                    ->whereHas('lesson', function ($query) use ($user) {
                        $query->where('teacher_id', $user->id);
                    })
                    ->get();
            } else {
                // For admins and moderators, fetch questions for any lesson by its ID
                $questions = Question::where('lesson_id', $lessonId)->get();
            }

            return response()->json(['questions' => $questions], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching questions.', 'error' => $e->getMessage()], 500);
        }
    }






    // In your LessonController.php
    public function getQuestionsByCourse($courseId)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is a teacher
        if (!$user->hasRole('teacher')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Retrieve the course by ID, ensuring the teacher owns it
        $course = Course::where('id', $courseId)
            ->where('teacher_id', $user->id)
            ->with('questions.responses') // Eager load questions and responses
            ->first();

        // If the course does not exist or doesn't belong to the teacher
        if (!$course) {
            return response()->json(['message' => 'Course not found or unauthorized.'], 404);
        }

        // Extract questions with responses
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

        // Return the questions in the response
        return response()->json(['questions' => $questions], 200);
    }








}
