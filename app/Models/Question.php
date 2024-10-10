<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'question_text'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'question_id');
    }
}
