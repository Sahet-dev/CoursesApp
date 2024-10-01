<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Webhook;

class SubscriptionController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $stripeSecret = config('services.stripe.key');
        Log::info('Stripe Secret: ' );
        Stripe::setApiKey($stripeSecret);

        try {
            // Define price IDs
            $monthlyPriceId = 'price_1Q2X3L2LpRd4wlvx2dicYVUK';
            $yearlyPriceId = 'prod_QuLlWgXdMd2rgn';

            // Choose the price ID based on the selected plan
            $selectedPriceId = $request->plan === 'monthly' ? $monthlyPriceId : $yearlyPriceId;

            // Create or retrieve the Stripe customer for the user
            $user = auth()->user();
            if (!$user->stripe_id) {
                $customer = \Stripe\Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);

                // Save the Stripe customer ID in the user record
                $user->stripe_id = $customer->id;
                $user->save();
            }

            // Create a Stripe Checkout Session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $selectedPriceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'customer' => $user->stripe_id, // Attach the Stripe customer ID to the session
                'success_url' => env('FRONTEND_URL', 'http://localhost:4000') . '/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('/cancel'),
            ]);

            // Return the session ID to the frontend
            return response()->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    private function getUserIdByStripeCustomerId($stripeCustomerId)
    {
        // Find the user by their Stripe customer ID
        $user = User::where('stripe_id', $stripeCustomerId)->first();

        // Return the user ID or null if not found
        return $user ? $user->id : null;
    }


    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // Assuming 'checkout.session.completed' event type
        if ($payload['type'] == 'checkout.session.completed') {
            $session = $payload['data']['object'];

            // Extract the necessary data
            $userId = $this->getUserIdByStripeCustomerId($session['customer']); // Method to find user by Stripe ID
            $plan = 'subscription'; // Replace this with the actual plan name if necessary
            $startsAt = now();
            $endsAt = now()->addMonth(); // Example for a monthly subscription
            $status = 'active';
            $price = $session['amount_total'] / 100; // Convert cents to dollars

            // Insert into subscriptions table
            DB::table('subscriptions')->insert([
                'user_id' => $userId,
                'plan' => $plan,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => $status,
                'price' => $price, // Add the price here
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }





    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:4000');
        return response()->json(['redirect' => $frontendUrl . '/success?session_id=' . $sessionId]);
    }

}
