<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueShare extends Model
{
    use HasFactory;

    protected $table = 'revenue_shares';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'teacher_id',
        'total_revenue',
        'teacher_earnings',
    ];
}
