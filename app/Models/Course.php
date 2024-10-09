<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'price',
        'thumbnail',
        'teacher_id',
        'premium',
        'subscription_access',
        'type'
    ];

    public function scopeWithBasicDetails(Builder $query): Builder
    {
        return $query->select('id', 'title', 'description',  'thumbnail');
    }


    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lessons::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('completed')->withTimestamps();
    }


    public function markUserAsCompleted(User $user): void
    {
        $this->users()->updateExistingPivot($user->id, ['completed' => true]);
    }

    public function isCompletedByUser(User $user): bool
    {
        return $this->users()->wherePivot('completed', true)->where('user_id', $user->id)->exists();
    }



    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function isPurchasedBy(User $user)
    {
        return $this->purchases()->where('user_id', $user->id)->exists();
    }


    public function engagements()
    {
        return $this->hasMany(Engagement::class);
    }

    public function isAvailableToUser(User $user): bool
    {
        // Check if the user has an active subscription or has purchased this course
        return $user->hasActiveSubscription() || $this->hasBeenPurchasedBy($user);
    }

// Example method to check if a course has been purchased by a user
    public function hasBeenPurchasedBy(User $user): bool
    {
        return $this->purchases()->where('user_id', $user->id)->exists();
    }


    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }


}
