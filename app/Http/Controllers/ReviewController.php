<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ], [
            'book_id.required' => 'Book id is mandatory.',
            'rating.required' => 'Rating is mandatory',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $reviews = new Review;
        $reviews->book_id = $request->book_id;
        $reviews->user_id  = Auth::id();
        $reviews->rating = $request->rating;
        $reviews->comment = $request->comment;
        $reviews->save();

        $user = Auth::user();
        $user->increment('points', 10);

        return back()->with('success', 'Review submitted successfully.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        $review->delete();
        return back()->with('success', 'Review Deleted.');
    }

    public function storeComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_id' => 'required|exists:reviews,id',
            'comment' => 'required|string',
        ], [
            'review_id.required' => 'Review id is mandatory.',
            'comment.required' => 'Comment is mandatory',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = new ReviewComment;
        $comment->review_id = $request->review_id;
        $comment->user_id  = Auth::id();
        $comment->comment = $request->comment;
        $comment->save();

        return back();
    }
}
