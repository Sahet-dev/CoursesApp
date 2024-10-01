<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Purchase;
use App\Models\Question;
use App\Models\RevenueShare;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeacherControlle extends Controller
{
    public function getOwnCourses()
    {
        $teacher = Auth::user();

        if (!$teacher->hasRole('teacher')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $courses = $teacher->ownedCourses()->with('lessons')->get();

        return response()->json($courses, 200);
    }

    public function getCourseEnrollments($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Get users who purchased the course
        $purchases = $course->purchases()->with('user')->get();
        $purchasedUsers = $purchases->map(function ($purchase) {
            return $purchase->user;
        });

        return response()->json($purchasedUsers);
    }

    // Fetch all subscriptions with their user data
    public function getSubscriptions(): JsonResponse
    {
        $subscriptions = Subscription::with('user')->get();

        return response()->json($subscriptions);
    }


    public function teacherPreview($id): JsonResponse
    {
        $course = Course::with('lessons')->find($id); // Assuming lessons are related to courses

        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        return response()->json($course);
    }




    public function teacherUpdate(Request $request, $id): JsonResponse
    {
        Log::info('not working');
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            // Add other fields as needed
        ]);

        // Update the course with validated data
        $course->update($validatedData);

        return response()->json(['message' => 'Course updated successfully.', 'course' => $course]);
    }

    // Method to delete a course
    public function teacherDestroy($id): JsonResponse
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        // Delete the course
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully.']);
    }



//TECHER FINANCES

    public function getDashboardData()
    {
        $teacherId = auth()->user()->id;

        // Total Revenue and Teacher Earnings
        $revenueShare = RevenueShare::where('teacher_id', $teacherId)->first();
        $totalRevenue = $revenueShare ? $revenueShare->total_revenue : 0;
        $teacherEarnings = $revenueShare ? $revenueShare->teacher_earnings : 0;

        // Recent Transactions
        $recentPurchases = Purchase::where('course_id', $this->getTeacherCourseIds($teacherId))
            ->latest()
            ->take(10)
            ->get();

        // Subscription Metrics
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $newSubscriptions = Subscription::getNewSubscriptions($startDate, $endDate);
        $churnRate = Subscription::getChurnRate($startDate, $endDate);



        return response()->json([
            'totalRevenue' => $totalRevenue,
            'teacherEarnings' => $teacherEarnings,
            'newSubscriptions' => $newSubscriptions,
            'churnRate' => $churnRate,
            'recentPurchases' => $recentPurchases,
        ]);
    }

    private function getTeacherCourseIds($teacherId)
    {
        return Course::where('teacher_id', $teacherId)->pluck('id');
    }


    public function showStudentPerformance(User $user): JsonResponse
    {
        $achievements = $user->achievements()->with('course')->get();
        $engagements = $user->engagements()->with('course')->get();
        $totalTimeSpent = $engagements->sum('time_spent');
        $totalInteractions = $engagements->sum('interactions');
        $totalAssignmentsCompleted = $engagements->sum('assignments_completed');
        $comments = $user->comments()->with('lesson')->get();
        $questions = Question::where('user_id', $user->id)->with('responses')->get();

        return response()->json([
            'achievements' => $achievements,
            'totalTimeSpent' => $totalTimeSpent,
            'totalInteractions' => $totalInteractions,
            'totalAssignmentsCompleted' => $totalAssignmentsCompleted,
            'comments' => $comments,
            'questions' => $questions,
        ]);
    }

























}
