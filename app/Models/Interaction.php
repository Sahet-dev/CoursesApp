<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'engagement_id',
        'interaction_type',
        'lesson_id',
        'timestamp',
    ];
    protected $casts = [
        'timestamp' => 'datetime',
    ];
    public function engagement()
    {
        return $this->belongsTo(Engagement::class);
    }
    public function lesson()
    {
        return $this->belongsTo(Lessons::class);
    }
}
