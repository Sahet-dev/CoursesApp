<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subscription;
use App\Models\User;
use App\Services\FinancialMetricsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $popularCourses = Course::withCount('users')
            ->orderBy('users_count', 'desc')
            ->take(5)
            ->get();

        $completionRates = Course::withCount([
            'users as completed_users_count' => function ($query) {
                $query->where('course_user.completed', true);
            },
            'users'
        ])->get()->map(function ($course) {
            return [
                'course' => $course->title,
                'completion_rate' => $course->users_count > 0
                    ? ($course->completed_users_count / $course->users_count) * 100
                    : 0
            ];
        });

        $engagementMetrics = Course::with(['engagements' => function ($query) {
            $query->selectRaw('course_id, AVG(time_spent) as avg_time_spent, AVG(assignments_completed) as avg_assignments_completed')
                ->groupBy('course_id');
        }, 'engagements.interactions' => function ($query) {
            $query->selectRaw('engagement_id, COUNT(*) as total_interactions')
                ->groupBy('engagement_id');
        }])
            ->get()
            ->map(function ($course) {
                $engagement = $course->engagements->first();
                return [
                    'course' => $course->title,
                    'avg_time_spent' => $engagement->avg_time_spent ?? 0,
                    'avg_interactions' => isset($engagement->interactions) ? $engagement->interactions->count() : 0,
                    'avg_assignments_completed' => $engagement->avg_assignments_completed ?? 0,
                ];
            });

        return response()->json([
            'popular_courses' => $popularCourses,
            'completion_rates' => $completionRates,
            'engagement_metrics' => $engagementMetrics
        ]);
    }

    public function getDashboardMetrics(Request $request)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $activeUsers = User::getActiveUsers($startDate, $endDate);
        $newSubscriptions = Subscription::getNewSubscriptions($startDate, $endDate);
        $churnRate = Subscription::getChurnRate($startDate, $endDate);

        $initialPeriodStart = $request->query('initial_period_start');
        $initialPeriodEnd = $request->query('initial_period_end');
        $retentionPeriodStart = $request->query('retention_period_start');
        $retentionPeriodEnd = $request->query('retention_period_end');
        $retentionRate = Subscription::getRetentionRate($initialPeriodStart, $initialPeriodEnd, $retentionPeriodStart, $retentionPeriodEnd);

        $financialStartDate = Carbon::now()->subMonth();
        $financialEndDate = Carbon::now();

        $totalRevenue = FinancialMetricsService::getTotalRevenue();
        $arpu = FinancialMetricsService::getARPU($financialStartDate, $financialEndDate);
        $ltv = FinancialMetricsService::getLTV();

        return response()->json([
            'active_users' => $activeUsers,
            'new_subscriptions' => $newSubscriptions,
            'churn_rate' => $churnRate,
            'retention_rate' => $retentionRate,
            'financial_metrics' => [
                'totalRevenue' => $totalRevenue,
                'arpu' => $arpu,
                'ltv' => $ltv
            ]
        ]);
    }

    public function financialMetrics()
    {
        if (!Auth::user()->hasRole(['admin', 'moderator', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $startDate = Carbon::now()->subMonth();
        $endDate = Carbon::now();

        $totalRevenue = FinancialMetricsService::getTotalRevenue();
        $arpu = FinancialMetricsService::getARPU($startDate, $endDate);
        $ltv = FinancialMetricsService::getLTV();

        return response()->json([
            'totalRevenue' => $totalRevenue,
            'arpu' => $arpu,
            'ltv' => $ltv,
        ]);
    }
}
