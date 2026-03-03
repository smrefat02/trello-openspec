<?php

namespace Trello\Policies;

use App\Models\User;
use Trello\Models\Card;

class CardPolicy
{
    /**
     * Determine whether the user can view the card.
     */
    public function view(User $user, Card $card): bool
    {
        return $card->list->board->user_id === $user->id;
    }

    /**
     * Determine whether the user can create cards.
     */
    public function create(User $user, ?int $listId = null): bool
    {
        if ($listId) {
            $list = \Trello\Models\TrelloList::find($listId);
            return $list && $list->board->user_id === $user->id;
        }
        return true;
    }

    /**
     * Determine whether the user can update the card.
     */
    public function update(User $user, Card $card): bool
    {
        return $card->list->board->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the card.
     */
    public function delete(User $user, Card $card): bool
    {
        return $card->list->board->user_id === $user->id;
    }
}
