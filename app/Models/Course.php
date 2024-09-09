<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'price',
        'teacher_id',
        'premium',
        'subscription_access'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lessons::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('completed')->withTimestamps();
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




}
