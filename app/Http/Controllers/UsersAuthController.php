<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class UsersAuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($request->only('email', 'password'))) {
            // Generate token
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            $cookie = Cookie::make('auth_token', $token, 60, '/', null, true, true, false, 'Strict');

            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $user,
            ])->withCookie($cookie);
        }

        return response()->json([
            'message' => 'Invalid email or password',
        ], 401);
    }

    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // Ensure 'password_confirmation' field is also submitted
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Log the user in
        Auth::login($user);

        // Generate token
        $token = $user->createToken('authToken')->plainTextToken;

        // Create HTTP-only cookie
        $cookie = Cookie::make('auth_token', $token, 60, '/', null, true, true, false, 'Strict');

        // Return success response with the cookie
        return response()->json([
            'message' => 'Registered successfully',
            'user' => $user,
        ])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Revoke the user's token
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

            // Clear the authentication cookie
            $cookie = Cookie::forget('auth_token');

            // Return success response
            return response()->json([
                'message' => 'Logged out successfully',
            ])->withCookie($cookie);
        }

        // Return error if no user is authenticated
        return response()->json([
            'message' => 'No authenticated user',
        ], 401);
    }


    public function getUser(Request $request): JsonResponse
    {
        $user = $request->user(); // Fetch the authenticated user, if any

        if ($user) {
            // If the user is authenticated, return user data
            return response()->json([
                'user' => new UserResource($user),
            ]);
        }

        // If no user is authenticated, return null or an appropriate message
        return response()->json([
            'user' => null, // Send a null user or an empty object
            'message' => 'User is not authenticated',
        ], 200);
    }
}
