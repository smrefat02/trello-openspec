<?php

namespace Trello\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrelloList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'board_id',
        'title',
        'position',
    ];

    /**
     * Get the board that owns the list.
     */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * Get the cards for the list.
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'list_id');
    }
}
