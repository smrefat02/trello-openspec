<?php

namespace Trello\Services;

use Trello\Models\TrelloList;
use Illuminate\Support\Facades\DB;

class ListService
{
    /**
     * Create a new list.
     */
    public function createList(array $data): TrelloList
    {
        return DB::transaction(function () use ($data) {
            // Calculate position if not provided
            if (!isset($data['position'])) {
                $maxPosition = TrelloList::where('board_id', $data['board_id'])->max('position');
                $data['position'] = ($maxPosition ?? 0) + 10;
            }

            return TrelloList::create($data);
        });
    }

    /**
     * Update an existing list.
     */
    public function updateList(TrelloList $list, array $data): TrelloList
    {
        return DB::transaction(function () use ($list, $data) {
            $list->update($data);
            return $list->fresh();
        });
    }

    /**
     * Delete a list.
     */
    public function deleteList(TrelloList $list): bool
    {
        return DB::transaction(function () use ($list) {
            return $list->delete();
        });
    }
}
