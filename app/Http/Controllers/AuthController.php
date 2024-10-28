<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request): Application|Response|ResponseFactory
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);


        if (!Auth::attempt($credentials, $remember)) {
            return response(['message' => 'Email or password is incorrect'], 422);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();


        $token = $user->createToken('main')->plainTextToken;


        return response([
            'user' => new UserResource($user),
            'token' => $token]);
    }

    public function logout(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        /** @var User $user */
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response('', 204);
    }

    public function getUser(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
