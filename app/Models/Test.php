<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;





    protected $fillable = ['lesson_id', 'title', 'description'];

//    public function lesson()
//    {
//        return $this->belongsTo(Lessons::class, 'lesson_id'); // Ensure the foreign key is correct
//    }
//
//    // Define the relationship to the Question model
//    public function questions()
//    {
//        return $this->hasMany(Question::class);
//    }
}
