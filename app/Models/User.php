<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'users',
        'is_active',
        'first_name',
        'lastname',
        'last_login_at',
        'gender',
        'age',
        'location',
        'avatar',
        'stripe_id'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ownedCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function markCourseAsCompleted(Course $course): void
    {
        $this->courses()->updateExistingPivot($course->id, ['completed' => true]);
    }

    public function isCourseCompleted(Course $course): bool
    {
        return $this->courses()->wherePivot('completed', true)->where('course_id', $course->id)->exists();
    }




    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

    public static function getActiveUsers($startDate, $endDate)
    {
        return self::where('is_active', true)
            ->whereBetween('last_login_at', [$startDate, $endDate])
            ->count();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('completed')->withTimestamps();
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(Engagement::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')->withTimestamps()->withPivot('earned_at');
    }


    public function purchasedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')->withPivot('completed')->withTimestamps();
    }

    public function hasPurchasedCourse(Course $course): bool
    {
        return $this->purchasedCourses->contains($course);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Course::class, 'bookmarks');
    }

    public function hasBookmarkedCourse(Course $course): bool
    {
        return $this->bookmarks()->where('course_id', $course->id)->exists();
    }



}
