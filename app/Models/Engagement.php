<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Engagement extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'user_id',
        'lesson_id',
        'time_spent',
        'interactions',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Append a new interaction to the interactions array.
     *
     * @param string $interactionType
     * @param int|null $lessonId
     * @param string $timestamp
     * @return void
     */
    public function addInteraction(string $interactionType, ?int $lessonId, string $timestamp)
    {
        // Structure of new interaction data
        $newInteraction = [
            'interaction_type' => $interactionType,
            'lesson_id' => $lessonId,
            'timestamp' => $timestamp,
        ];

        $interactions = is_array($this->interactions) ? $this->interactions : [];

        $interactions[] = $newInteraction;

        $this->interactions = $interactions;

        $this->save();
    }


}
