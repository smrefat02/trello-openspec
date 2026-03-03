<?php

namespace Trello\Services;

use Trello\Models\Card;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CardService
{
    /**
     * Create a new card.
     */
    public function createCard(array $data): Card
    {
        return DB::transaction(function () use ($data) {
            // Calculate position if not provided
            if (!isset($data['position'])) {
                $maxPosition = Card::where('list_id', $data['list_id'])->max('position');
                $data['position'] = ($maxPosition ?? 0) + 10;
            }

            return Card::create($data);
        });
    }

    /**
     * Update an existing card.
     */
    public function updateCard(Card $card, array $data): Card
    {
        return DB::transaction(function () use ($card, $data) {
            $card->update($data);
            return $card->fresh();
        });
    }

    /**
     * Delete a card.
     */
    public function deleteCard(Card $card): bool
    {
        return DB::transaction(function () use ($card) {
            return $card->delete();
        });
    }

    /**
     * Update card position.
     */
    public function updatePosition(Card $card, int $position, ?int $newListId = null): Card
    {
        return DB::transaction(function () use ($card, $position, $newListId) {
            $updateData = ['position' => $position];
            
            if ($newListId && $newListId !== $card->list_id) {
                $updateData['list_id'] = $newListId;
            }
            
            $card->update($updateData);
            return $card->fresh();
        });
    }

    /**
     * Search and filter cards with pagination.
     */
    public function searchAndFilterCards(
        ?string $search = null,
        ?string $status = null,
        ?string $priority = null,
        ?int $listId = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Card::query();

        if ($listId) {
            $query->where('list_id', $listId);
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        return $query->orderBy('position', 'asc')->paginate($perPage);
    }
}
