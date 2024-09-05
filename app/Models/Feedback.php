<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'badge_icon', // optional
        'criteria', // e.g., "Complete 5 courses"
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')->withTimestamps();
    }
}
