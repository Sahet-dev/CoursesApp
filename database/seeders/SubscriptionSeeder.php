<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            [
                'user_id' => 3, // Ensure this user exists
                'plan' => 'basic',
                'starts_at' => Carbon::now()->subMonths(1), // Subscription started a month ago
                'price' => 9.99,
                'ends_at' => Carbon::now()->addMonths(1), // Subscription ends in a month
                'status' => 'active',
            ],
            [
                'user_id' => 2, // Ensure this user exists
                'plan' => 'premium',
                'starts_at' => Carbon::now()->subDays(10), // Subscription started 10 days ago
                'price' => 19.99,
                'ends_at' => Carbon::now()->addMonths(2), // Subscription ends in 2 months
                'status' => 'active',
            ],
            // Add more subscriptions as needed
        ]);
    }
}
