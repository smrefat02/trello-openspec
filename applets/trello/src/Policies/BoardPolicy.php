<?php

namespace Trello\Policies;

use App\Models\User;
use Trello\Models\Board;

class BoardPolicy
{
    /**
     * Determine whether the user can view any boards.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the board.
     */
    public function view(User $user, Board $board): bool
    {
        return $board->user_id === $user->id || $board->visibility === 'public';
    }

    /**
     * Determine whether the user can create boards.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the board.
     */
    public function update(User $user, Board $board): bool
    {
        return $board->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the board.
     */
    public function delete(User $user, Board $board): bool
    {
        return $board->user_id === $user->id;
    }
}
