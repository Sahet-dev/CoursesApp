<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'title', 'video_url', 'markdown_text'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    public function comments()
    {
        return $this->hasMany(Comment::class, 'lesson_id');
    }
}
