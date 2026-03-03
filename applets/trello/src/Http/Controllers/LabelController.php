<?php

namespace Trello\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Trello\Models\Card;
use Trello\Models\CardLabel;

class LabelController
{
    use AuthorizesRequests;

    /**
     * Store a newly created label.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'name' => 'required|string|max:100',
            'color' => 'required|in:purple,green,yellow,orange,red,blue,pink,gray',
        ]);

        $card = Card::findOrFail($validated['card_id']);
        $this->authorize('update', $card);

        $maxPosition = CardLabel::where('card_id', $card->id)->max('position') ?? 0;
        
        $label = CardLabel::create([
            'card_id' => $card->id,
            'name' => $validated['name'],
            'color' => $validated['color'],
            'position' => $maxPosition + 10,
        ]);

        return response()->json([
            'success' => true,
            'label' => $label,
        ]);
    }

    /**
     * Remove the specified label.
     */
    public function destroy(CardLabel $label)
    {
        $this->authorize('update', $label->card);

        $label->delete();

        return response()->json([
            'success' => true,
            'message' => 'Label deleted successfully.',
        ]);
    }
}
