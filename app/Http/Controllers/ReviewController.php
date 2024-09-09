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

        // Redirect to a page or reload the current page
        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function index()
    {
        $user =  Auth::user();

        $reviews = Review::with('user')->get();

        return Inertia::render('Reviews/Index', [
            'user' => $user,
            'reviews' => $reviews,
        ]);
    }
}
