<?php

namespace Trello\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardLabel extends Model
{
    protected $fillable = [
        'card_id',
        'name',
        'color',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    /**
     * Get the card that owns the label.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
