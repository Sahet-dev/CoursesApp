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
        // Debugging: Log all incoming request data
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $lesson = Lessons::findOrFail($id);

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



    public function createQuestionsForLesson(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|integer|exists:lessons,id',  // Ensure lesson_id is valid
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.responses' => 'required|array',
            'questions.*.responses.*.response_text' => 'required|string',
            'questions.*.responses.*.is_correct' => 'required|boolean',
        ]);

        $lessonId = $validated['lesson_id'];
        $questions = $validated['questions'];

        foreach ($questions as $questionData) {
            // Create a question associated with the lesson
            $question = Question::create([
                'lesson_id' => $lessonId,
                'question_text' => $questionData['question_text'],
            ]);

            // Create responses for the question
            foreach ($questionData['responses'] as $responseData) {
                $question->responses()->create([
                    'response_text' => $responseData['response_text'],
                    'is_correct' => $responseData['is_correct'],
                ]);
            }
        }

        return response()->json(['message' => 'Questions and responses added successfully!'], 201);
    }
    public function updateQuestionsForLesson(Request $request, $lessonId)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|integer|exists:lessons,id',  // Ensure lesson_id is valid
            'questions' => 'required|array',
            'questions.*.id' => 'nullable|integer|exists:questions,id',  // Allow for both creating new and updating existing questions
            'questions.*.question_text' => 'required|string',
            'questions.*.responses' => 'required|array',
            'questions.*.responses.*.id' => 'nullable|integer|exists:responses,id',  // Allow for both creating new and updating existing responses
            'questions.*.responses.*.response_text' => 'required|string',
            'questions.*.responses.*.is_correct' => 'required|boolean',
        ]);

        $lesson = Lessons::findOrFail($lessonId);

        foreach ($validated['questions'] as $questionData) {
            if (isset($questionData['id'])) {
                // Update existing question
                $question = Question::findOrFail($questionData['id']);
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
                $question = $lesson->questions()->create([
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
            // Fetch questions directly related to the lesson
            $questions = Question::where('lesson_id', $lessonId)->get();

            return response()->json(['questions' => $questions], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching questions.'], 500);
        }
    }


    public function updateTest(Request $request, $id)
    {
        //
    }



    // In your LessonController.php
    public function getQuestionsByLesson($lessonId)
    {
        // Load the lesson with related questions and their responses
        $lesson = Lessons::with('questions.responses')->find($lessonId);

        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found.'], 404);
        }

        // Extract questions and responses directly from the lesson
        $questions = $lesson->questions->map(function ($question) {
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

        return response()->json(['questions' => $questions]);
    }





}
