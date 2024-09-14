<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseModerationController extends Controller
{
    public function index(): JsonResponse
    {
        // Fetch reviews with pagination, ordered by 'created_at' in ascending order
        $reviews = Review::with('user')->orderBy('created_at', 'desc')->paginate(5); // Change 5 to the number of reviews you want per page

        return response()->json($reviews);
    }

    public function updateReview(Request $request, $id): JsonResponse
    {
        $review = Review::findOrFail($id);
        $review->update($request->only(['comment', 'rating'])); // Adjust fields as needed

        return response()->json(['message' => 'Review updated successfully.', 'review' => $review]);
    }

    public function deleteReview($id): JsonResponse
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully.']);
    }







    public function getComment(): JsonResponse
    {
        // Fetch all comments with pagination, ordered by 'created_at' in descending order
        $comments = Comment::with(['user', 'lesson', 'likes', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($comments);
    }

    public function updateComment($id, Request $request)
    {
        $comment = Comment::findOrFail($id);

        // Validate the request data
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        // Update the comment
        $comment->update(['comment' => $request->comment]);

        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
    }


    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);

        // Delete the comment
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
