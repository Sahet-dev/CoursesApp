<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'active_users',
        'new_subscriptions',
        'churn_rate',
        'retention_rate',
    ];
}
