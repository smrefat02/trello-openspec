<?php

namespace Trello\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Trello\Services\CardService;
use Trello\Models\Card;
use Trello\Http\Requests\StoreCardRequest;
use Trello\Http\Requests\UpdateCardRequest;

class CardController
{
    use AuthorizesRequests;
    protected $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    /**
     * Store a newly created card in storage.
     */
    public function store(StoreCardRequest $request)
    {
        $this->authorize('create', [Card::class, $request->list_id]);

        $card = $this->cardService->createCard($request->validated());

        $list = $card->list;
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Card created successfully.',
                'card' => $card
            ]);
        }
        
        return redirect()->route('boards.show', $list->board_id)
            ->with('success', 'Card created successfully.');
    }

    /**
     * Display the specified card.
     */
    public function show(Card $card)
    {
        $this->authorize('view', $card);

        $card->load([
            'labels',
            'checklists.items',
            'comments.user',
            'attachments',
            'list'
        ]);

        return response()->json([
            'success' => true,
            'card' => $card
        ]);
    }

    /**
     * Update the specified card in storage.
     */
    public function update(UpdateCardRequest $request, Card $card)
    {
        $this->authorize('update', $card);

        $card = $this->cardService->updateCard($card, $request->validated());

        $list = $card->list;
        return redirect()->route('boards.show', $list->board_id)
            ->with('success', 'Card updated successfully.');
    }

    /**
     * Remove the specified card from storage.
     */
    public function destroy(Card $card)
    {
        $this->authorize('delete', $card);

        $list = $card->list;
        $boardId = $list->board_id;
        
        $this->cardService->deleteCard($card);

        return redirect()->route('boards.show', $boardId)
            ->with('success', 'Card deleted successfully.');
    }

    /**
     * Move a card to a different list.
     */
    public function move(Card $card, \Illuminate\Http\Request $request)
    {
        $this->authorize('update', $card);

        $request->validate([
            'list_id' => 'required|exists:trello_lists,id',
        ]);

        $card->update(['list_id' => $request->list_id]);

        return response()->json([
            'success' => true,
            'message' => 'Card moved successfully.'
        ]);
    }
}
