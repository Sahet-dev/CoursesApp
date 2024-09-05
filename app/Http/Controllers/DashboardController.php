<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Retrieve data needed for the dashboard
        $user = auth()->user();

        // Load necessary relationships and data for dashboard components
        $enrolledCourses = $user->courses()->with('lessons')->get();
        $recentActivity = $user->engagements()->latest()->limit(5)->get();
        $currentPlan = $user->subscriptions()->where('status', 'active')->first();
        $notifications = $user->notifications()->latest()->limit(10)->get();
        $achievements = $user->achievements()->with('course')->get();
        $badges = $user->achievements()->with('badge')->get();
        $feedbacks = Feedback::all(); // If you want all feedbacks or modify as needed

        // Pass data to the Dashboard view
        return Inertia::render('Dashboard', [
            'enrolledCourses' => $enrolledCourses,
            'recentActivity' => $recentActivity,
            'currentPlan' => $currentPlan,
            'notifications' => $notifications,
            'achievements' => $achievements,
            'badges' => $badges,
            'feedbacks' => $feedbacks,
        ]);
    }
}
