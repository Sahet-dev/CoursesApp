<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UsersAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // Ensure 'password_confirmation' field is also submitted
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        Auth::login($user);

        $token = $user->createToken('authToken')->plainTextToken;

        $cookie = Cookie::make('auth_token', $token, 60, '/', null, true, true, false, 'Strict');

        return response()->json([
            'message' => 'Registered successfully',
            'user' => $user,
        ])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

            $cookie = Cookie::forget('auth_token');

            return response()->json([
                'message' => 'Logged out successfully',
            ])->withCookie($cookie);
        }

        return response()->json([
            'message' => 'No authenticated user',
        ], 401);
    }


    public function getUser(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            return response()->json([
                'user' => new UserResource($user),
            ]);
        }

        return response()->json([
            'user' => null,
            'message' => 'User is not authenticated',
        ], 200);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $path = $avatar->store('avatars', 'public');

            // Update user's avatar path in the database
            $user->avatar = $path;
            $user->save();

            return response()->json([
                'message' => 'Avatar uploaded successfully!',
                'user' => $user->only(['id', 'name', 'email', 'avatar']),
            ]);
        }

        return response()->json([
            'message' => 'No file uploaded.',
        ], 400); // Return a 400 Bad Request if no file was uploaded
    }



    public function getUserProfileData()
    {

        Log::info(Auth::user()->name . ' editing profile');
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $user = Auth::user();

        return response()->json($user);
    }

    public function updateUserProfile(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'age' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($validatedData);

        return response()->json(['message' => 'Profile updated successfully!']);
    }
}
