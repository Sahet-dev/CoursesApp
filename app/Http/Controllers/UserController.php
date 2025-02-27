<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function updateRole(Request $request, $id): JsonResponse
    {
        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (Auth::id() === $user->id) {
            return response()->json(['message' => 'Cannot update your own role'], 403);
        }



        $user->role = $request->input('role');
        $user->save();

        return response()->json(['message' => 'User role updated successfully'], 200);
    }




    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return new UserResource($user);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create', User::class)) {
            Log::error('Unauthorized access attempt to create a user', ['user_id' => $request->user()->id]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

//    public function update(Request $request, $id): JsonResponse
//    {
//        if (!Gate::allows('update', User::class)) {
//            Log::error('Unauthorized access attempt to update a user', ['user_id' => $request->user()->id]);
//            return response()->json(['message' => 'Unauthorized'], 403);
//        }
//
//        $validated = $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
//        ]);
//
//        $user = User::findOrFail($id);
//        $user->update($validated);
//
//        return response()->json($user);
//    }

    public function destroy(Request $request, $id): JsonResponse
    {
        if (!Gate::allows('delete', User::class)) {
            Log::error('Unauthorized access attempt to delete a user', ['user_id' => $request->user()->id]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }



}
