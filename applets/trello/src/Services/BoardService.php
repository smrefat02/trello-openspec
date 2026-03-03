<?php

namespace Trello\Services;

use Trello\Models\Board;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BoardService
{
    /**
     * Create a new board.
     */
    public function createBoard(array $data): Board
    {
        return DB::transaction(function () use ($data) {
            return Board::create($data);
        });
    }

    /**
     * Update an existing board.
     */
    public function updateBoard(Board $board, array $data): Board
    {
        return DB::transaction(function () use ($board, $data) {
            $board->update($data);
            return $board->fresh();
        });
    }

    /**
     * Delete a board.
     */
    public function deleteBoard(Board $board): bool
    {
        return DB::transaction(function () use ($board) {
            return $board->delete();
        });
    }

    /**
     * Search boards with pagination.
     */
    public function searchBoards(?string $search = null, int $userId = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Board::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
