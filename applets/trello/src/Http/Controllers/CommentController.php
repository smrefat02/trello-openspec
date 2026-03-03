<?php

namespace Trello\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Trello\Models\Card;
use Trello\Models\CardComment;

class CommentController
{
    use AuthorizesRequests;

    /**
     * Store a newly created comment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'comment' => 'required|string',
        ]);

        $card = Card::findOrFail($validated['card_id']);
        $this->authorize('view', $card);

        $comment = CardComment::create([
            'card_id' => $card->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
        ]);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(CardComment $comment)
    {
        // Only the comment author can delete it
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }
}
