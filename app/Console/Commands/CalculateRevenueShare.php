<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\RevenueShareService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CalculateRevenueShare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-revenue-share';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        Log::info('Running revenue share calculation');
        // Get the total revenue from active subscriptions
        $totalRevenue = Subscription::where('status', 'active')->sum('price');

        if ($totalRevenue <= 0) {
            $this->info('No revenue to distribute.');
            return;
        }

        // Initialize the service with total revenue
        $revenueService = new RevenueShareService($totalRevenue);

        // Calculate and distribute earnings
        $revenueService->calculateTeacherEarnings();

        $this->info('Revenue share calculation completed successfully.');
    }

}
