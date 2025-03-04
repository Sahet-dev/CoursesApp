<?php

namespace App\Http\Controllers;

use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    public function sendVerificationCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $code = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Generates 000 - 999
        $expiresAt = Carbon::now()->addMinutes(30);

        EmailVerification::updateOrCreate(
            ['email' => $request->email],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        // Send email
        Mail::raw("Your verification code is: $code", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your Verification Code');
        });

        return response()->json(['message' => 'Verification code sent.'], 200);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:3'
        ]);

        $verification = EmailVerification::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$verification) {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }

        if ($verification->isExpired()) {
            return response()->json(['message' => 'Verification code has expired. Please request a new one.'], 400);
        }

        // Mark email as verified
        User::where('email', $request->email)->update(['email_verified_at' => now()]);

        // Delete verification code
        $verification->delete();

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }
}
