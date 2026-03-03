<?php

namespace Trello\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'list_id',
        'title',
        'description',
        'due_date',
        'status',
        'priority',
        'position',
    ];

    protected $casts = [
        'due_date' => 'date',
        'status' => 'string',
        'priority' => 'string',
    ];

    /**
     * Get the list that owns the card.
     */
    public function list(): BelongsTo
    {
        return $this->belongsTo(TrelloList::class, 'list_id');
    }

    /**
     * Get the labels for the card.
     */
    public function labels(): HasMany
    {
        return $this->hasMany(CardLabel::class)->orderBy('position');
    }

    /**
     * Get the checklists for the card.
     */
    public function checklists(): HasMany
    {
        return $this->hasMany(CardChecklist::class)->orderBy('position');
    }

    /**
     * Get the comments for the card.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(CardComment::class)->latest();
    }

    /**
     * Get the attachments for the card.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(CardAttachment::class)->latest();
    }

    /**
     * Get the members assigned to the card.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'card_members')->withTimestamps();
    }
}

