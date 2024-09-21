<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'question_text'];

    // Define the relationship with the Course model
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Define the relationship with the Response model
    public function responses()
    {
        return $this->hasMany(Response::class, 'question_id');
    }
}
