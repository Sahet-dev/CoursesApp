<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function course()
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

        // Ensure interactions is treated as an array
        $interactions = is_array($this->interactions) ? $this->interactions : [];

        // Append the new interaction to the existing interactions array
        $interactions[] = $newInteraction;

        // Update the interactions field
        $this->interactions = $interactions;

        // Save the model
        $this->save();
    }


}
