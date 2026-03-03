<?php

namespace Trello\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Trello\Services\ListService;
use Trello\Models\TrelloList;
use Trello\Http\Requests\StoreListRequest;
use Trello\Http\Requests\UpdateListRequest;

class ListController
{
    use AuthorizesRequests;
    protected $listService;

    public function __construct(ListService $listService)
    {
        $this->listService = $listService;
    }

    /**
     * Store a newly created list in storage.
     */
    public function store(StoreListRequest $request)
    {
        $this->authorize('create', [TrelloList::class, $request->board_id]);

        $list = $this->listService->createList($request->validated());

        return redirect()->route('boards.show', $request->board_id)
            ->with('success', 'List created successfully.');
    }

    /**
     * Update the specified list in storage.
     */
    public function update(UpdateListRequest $request, TrelloList $list)
    {
        $this->authorize('update', $list);

        $list = $this->listService->updateList($list, $request->validated());

        return redirect()->route('boards.show', $list->board_id)
            ->with('success', 'List updated successfully.');
    }

    /**
     * Remove the specified list from storage.
     */
    public function destroy(TrelloList $list)
    {
        $this->authorize('delete', $list);

        $boardId = $list->board_id;
        $this->listService->deleteList($list);

        return redirect()->route('boards.show', $boardId)
            ->with('success', 'List deleted successfully.');
    }
}
