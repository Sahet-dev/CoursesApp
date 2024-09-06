<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'user_id', 'comment'];

    public function lesson()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'comment_id');
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'comment_id');
    }

}
