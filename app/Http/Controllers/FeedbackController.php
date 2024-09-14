<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:support,feedback',
            'message' => 'required|string',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Feedback submitted successfully');
    }

    public function index()
    {
        $feedbacks = Feedback::paginate(10);

        return response()->json([
            'success' => true,
            'data' => $feedbacks,
            'message' => 'Feedbacks retrieved successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return redirect()->route('admin.feedback.index')->with('success', 'Feedback deleted successfully');
    }
}
