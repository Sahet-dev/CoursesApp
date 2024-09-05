<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialMetricsService
{
    public static function getTotalRevenue()
    {
        $courseRevenue = Course::sum('price');
        $subscriptionRevenue = Subscription::where('status', 'active')->sum('plan');
        return $courseRevenue + $subscriptionRevenue;
    }

    public static function getRevenueByCourse()
    {
        return Course::select('courses.id', 'courses.title', 'courses.description', 'courses.thumbnail', DB::raw('SUM(courses.price) as revenue'))
            ->join('course_user', 'courses.id', '=', 'course_user.course_id')
            ->groupBy('courses.id', 'courses.title', 'courses.description', 'courses.thumbnail')
            ->get();
    }

    public static function getARPU($startDate, $endDate)
    {
        $totalRevenue = self::getTotalRevenue();
        $activeUsers = User::getActiveUsers($startDate, $endDate);
        return $activeUsers > 0 ? $totalRevenue / $activeUsers : 0;
    }

    public static function getLTV()
    {
        $arpu = self::getARPU(Carbon::now()->subYear(), Carbon::now());
        $averageLifespan = Subscription::where('status', 'active')
            ->avg(DB::raw('DATEDIFF(ends_at, starts_at) / 365'));
        return $arpu * $averageLifespan;
    }
}
