<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    protected $fillable = ['user_id', 'topic_id', 'title', 'visibility', 'content', 'recap'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(NoteQuestion::class);
    }

    public function linkedNotes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'note_links', 'note_id', 'linked_note_id');
    }

    public function linkedFrom(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'note_links', 'linked_note_id', 'note_id');
    }
}
