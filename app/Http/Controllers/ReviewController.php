<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($request->all());

        return response()->json([
            'message' => 'Review submitted successfully!',
            'review' => $review, // Optionally return the created review
        ], 201); // 201 Created status code
    }


    public function index()
    {
        $user = Auth::user();

        $reviews = Review::with('user')->get();

        return response()->json([

        'reviews' => $reviews,
    ]);
    }
}
