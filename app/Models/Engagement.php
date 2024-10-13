<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Engagement extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'user_id',
        'lesson_id',
        'time_spent',
        'assignments_completed',
        'completed',
    ];

    protected $casts = [
        'interactions' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class);
    }

    /**
     * Append a new interaction.
     *
     * @param string $interactionType
     * @param int|null $lessonId
     * @param string $timestamp
     * @return void
     */
    public function addInteraction(string $interactionType, ?int $lessonId, string $timestamp)
    {
        $this->interactions()->create([
            'interaction_type' => $interactionType,
            'lesson_id' => $lessonId,
            'timestamp' => $timestamp,
        ]);

    }




}
