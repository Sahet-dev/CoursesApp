<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class EmailVerificationController extends Controller
{
    public function user(Request $request)
    {
        return response()->json(auth()->user());
    }

    public function sendVerificationCode(Request $request): JsonResponse
    {
        // Ensure the user is authenticated
        $user = $request->user(); // Get authenticated user

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $email = $user->email;

        if (!$email) {
            return response()->json(['message' => 'Email is required.'], 400);
        }

        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $code = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(30);

        EmailVerification::updateOrCreate(
            ['email' => $email],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        try {
            Mail::to($email)->send(new VerificationCodeMail($code));
        } catch (\Exception $e) {
            Log::error("Mail sending failed: " . $e->getMessage());
            return response()->json(['message' => 'Failed to send verification email.'], 500);
        }

        return response()->json(['message' => 'Verification code sent.'], 200);
    }


    public function verifyCode(Request $request): JsonResponse
    {
        $user = auth()->user();
        $email = $request->email ?? $user?->email;

        if (!$email) {
            return response()->json(['message' => 'Email is required.'], 400);
        }

        $request->validate([
            'code' => 'required|digits:3'
        ]);

        $verification = EmailVerification::where('email', $email)
            ->where('code', $request->code)
            ->first();

        if (!$verification) {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }

        if ($verification->isExpired()) {
            return response()->json(['message' => 'Verification code has expired. Please request a new one.'], 400);
        }

        // Mark email as verified
        User::where('email', $email)->update(['email_verified_at' => now()]);

        // Delete verification code
        $verification->delete();

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }


    public function sendPasswordResetLink(Request $request): JsonResponse
    {
        // Validate the email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent. Please check your email.'], 200);
        } else {
            return response()->json(['message' => 'Failed to send password reset link.'], 400);
        }
    }
}
