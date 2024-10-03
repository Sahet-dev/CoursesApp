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

        $subscriptionRevenue = Subscription::where('status', 'active')->sum('plan');
        return $subscriptionRevenue > 0 ? $subscriptionRevenue : 0;
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
