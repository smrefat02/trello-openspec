<?php

namespace Trello\Policies;

use App\Models\User;
use Trello\Models\TrelloList;

class ListPolicy
{
    /**
     * Determine whether the user can create lists.
     */
    public function create(User $user, ?int $boardId = null): bool
    {
        if ($boardId) {
            $board = \Trello\Models\Board::find($boardId);
            return $board && $board->user_id === $user->id;
        }
        return true;
    }

    /**
     * Determine whether the user can update the list.
     */
    public function update(User $user, TrelloList $list): bool
    {
        return $list->board->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the list.
     */
    public function delete(User $user, TrelloList $list): bool
    {
        return $list->board->user_id === $user->id;
    }
}
