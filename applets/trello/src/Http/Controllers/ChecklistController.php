<?php

namespace Trello\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Trello\Models\Card;
use Trello\Models\CardChecklist;

class ChecklistController
{
    use AuthorizesRequests;

    /**
     * Store a newly created checklist.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'title' => 'required|string|max:255',
        ]);

        $card = Card::findOrFail($validated['card_id']);
        $this->authorize('update', $card);

        $maxPosition = CardChecklist::where('card_id', $card->id)->max('position') ?? 0;
        
        $checklist = CardChecklist::create([
            'card_id' => $card->id,
            'title' => $validated['title'],
            'position' => $maxPosition + 10,
        ]);

        return response()->json([
            'success' => true,
            'checklist' => $checklist->load('items'),
        ]);
    }

    /**
     * Remove the specified checklist.
     */
    public function destroy(CardChecklist $checklist)
    {
        $this->authorize('update', $checklist->card);

        $checklist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Checklist deleted successfully.',
        ]);
    }
}
