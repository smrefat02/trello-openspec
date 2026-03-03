<?php

namespace Trello\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Trello\Models\CardChecklist;
use Trello\Models\ChecklistItem;

class ChecklistItemController
{
    use AuthorizesRequests;

    /**
     * Store a newly created checklist item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'checklist_id' => 'required|exists:card_checklists,id',
            'title' => 'required|string|max:500',
        ]);

        $checklist = CardChecklist::findOrFail($validated['checklist_id']);
        $this->authorize('update', $checklist->card);

        $maxPosition = ChecklistItem::where('checklist_id', $checklist->id)->max('position') ?? 0;
        
        $item = ChecklistItem::create([
            'checklist_id' => $checklist->id,
            'title' => $validated['title'],
            'is_completed' => false,
            'position' => $maxPosition + 10,
        ]);

        return response()->json([
            'success' => true,
            'item' => $item,
            'checklist' => $checklist->load('items'),
        ]);
    }

    /**
     * Toggle the completion status of a checklist item.
     */
    public function toggle(ChecklistItem $item)
    {
        $this->authorize('update', $item->checklist->card);

        $item->update([
            'is_completed' => !$item->is_completed,
        ]);

        return response()->json([
            'success' => true,
            'item' => $item,
            'checklist' => $item->checklist->load('items'),
        ]);
    }

    /**
     * Remove the specified checklist item.
     */
    public function destroy(ChecklistItem $item)
    {
        $this->authorize('update', $item->checklist->card);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully.',
        ]);
    }
}
