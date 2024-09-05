<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notifiable_id' => User::factory(),
            'notifiable_type' => User::class,
            'data' => ['message' => $this->faker->sentence],
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
