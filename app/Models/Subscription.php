<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'plan',
        'starts_at',
        'ends_at',
        'status',
        'price',
    ];



    public static function getNewSubscriptions($startDate, $endDate)
    {
        return self::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    /**
     * Calculate the churn rate within a given date range.
     */
    public static function getChurnRate($startDate, $endDate)
    {
        $totalSubscriptions = self::whereBetween('starts_at', [$startDate, $endDate])->count();

        $canceledSubscriptions = self::where('status', 'canceled')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        return $totalSubscriptions > 0 ? ($canceledSubscriptions / $totalSubscriptions) * 100 : 0;
    }

    public static function getRetentionRate($initialPeriodStart, $initialPeriodEnd, $retentionPeriodStart, $retentionPeriodEnd)
    {
        $initialUsers = self::whereBetween('starts_at', [$initialPeriodStart, $initialPeriodEnd])
            ->pluck('user_id')
            ->unique();

        $retainedUsers = self::whereIn('user_id', $initialUsers)
            ->whereBetween('starts_at', [$retentionPeriodStart, $retentionPeriodEnd])
            ->count();

        return $initialUsers->count() > 0 ? ($retainedUsers / $initialUsers->count()) * 100 : 0;
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'subscription_course');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
