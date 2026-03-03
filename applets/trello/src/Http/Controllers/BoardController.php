<?php

namespace Trello\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Trello\Services\BoardService;
use Trello\Models\Board;
use Trello\Http\Requests\StoreBoardRequest;
use Trello\Http\Requests\UpdateBoardRequest;

class BoardController
{
    use AuthorizesRequests;
    protected $boardService;

    public function __construct(BoardService $boardService)
    {
        $this->boardService = $boardService;
    }

    /**
     * Display a listing of the boards.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $boards = $this->boardService->searchBoards($search, Auth::id());

        return view('trello::boards.index', compact('boards', 'search'));
    }

    /**
     * Show the form for creating a new board.
     */
    public function create()
    {
        return view('trello::boards.create');
    }

    /**
     * Store a newly created board in storage.
     */
    public function store(StoreBoardRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $board = $this->boardService->createBoard($data);

        return redirect()->route('boards.show', $board)
            ->with('success', 'Board created successfully.');
    }

    /**
     * Display the specified board.
     */
    public function show(Board $board, Request $request)
    {
        $this->authorize('view', $board);

        $board->load(['lists.cards' => function ($query) {
            $query->orderBy('position', 'asc')
                  ->with(['labels', 'checklists.items', 'comments.user', 'attachments', 'members']);
        }]);

        return view('trello::boards.show', compact('board'));
    }

    /**
     * Show the form for editing the specified board.
     */
    public function edit(Board $board)
    {
        $this->authorize('update', $board);

        return view('trello::boards.edit', compact('board'));
    }

    /**
     * Update the specified board in storage.
     */
    public function update(UpdateBoardRequest $request, Board $board)
    {
        $this->authorize('update', $board);

        $board = $this->boardService->updateBoard($board, $request->validated());

        return redirect()->route('boards.show', $board)
            ->with('success', 'Board updated successfully.');
    }

    /**
     * Remove the specified board from storage.
     */
    public function destroy(Board $board)
    {
        $this->authorize('delete', $board);

        $this->boardService->deleteBoard($board);

        return redirect()->route('boards.index')
            ->with('success', 'Board deleted successfully.');
    }
}
