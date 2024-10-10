<?php

namespace App\Services;

use App\Models\Course;
use App\Models\RevenueShare;
use App\Models\User;

class RevenueShareService
{
    protected $totalRevenue;

    public function __construct(float $totalRevenue)
    {
        $this->totalRevenue = $totalRevenue;
    }

    public function calculateTeacherEarnings(): void
    {
        $teachers = User::where('role', 'teacher')->with('courses.engagements')->get();
        $totalMetricScore = $this->calculateTotalMetricScore();

        foreach ($teachers as $teacher) {
            $teacherMetricScore = $this->calculateTeacherMetricScore($teacher);
            $teacherEarnings = ($teacherMetricScore / $totalMetricScore) * $this->totalRevenue;

            RevenueShare::create([
                'teacher_id' => $teacher->id,
                'total_revenue' => $this->totalRevenue,
                'teacher_earnings' => $teacherEarnings,
            ]);
        }
    }

    protected function calculateTotalMetricScore(): float|int
    {
        $totalScore = 0;
        $courses = Course::with('engagements')->get();

        foreach ($courses as $course) {
            $totalScore += $this->calculateCourseMetricScore($course);
        }

        return $totalScore;
    }

    protected function calculateTeacherMetricScore($teacher): float|int
    {
        $score = 0;

        foreach ($teacher->courses as $course) {
            $score += $this->calculateCourseMetricScore($course);
        }

        return $score;
    }

    protected function calculateCourseMetricScore($course): float
    {
        $engagements = $course->engagements;
        $enrollments = $engagements->count();
        $completionRate = $engagements->where('completed', true)->count() / max($enrollments, 1);
        $timeSpent = $engagements->sum('time_spent');

        return (0.3 * $enrollments) + (0.4 * $completionRate) + (0.3 * $timeSpent);
    }
}
