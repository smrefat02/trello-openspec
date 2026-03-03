<?php

namespace Trello\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardChecklist extends Model
{
    protected $fillable = [
        'card_id',
        'title',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    /**
     * Get the card that owns the checklist.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the checklist items for the checklist.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class, 'checklist_id')->orderBy('position');
    }

    /**
     * Get the completion percentage of the checklist.
     */
    public function getCompletionPercentageAttribute(): int
    {
        $total = $this->items->count();
        if ($total === 0) {
            return 0;
        }
        
        $completed = $this->items->where('is_completed', true)->count();
        return (int) round(($completed / $total) * 100);
    }
}
