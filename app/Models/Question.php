<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'question_text'];  // Ensure lesson_id is fillable

    public function lesson()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'question_id');
    }
}
