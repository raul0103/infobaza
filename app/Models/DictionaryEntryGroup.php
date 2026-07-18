<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class DictionaryEntryGroup extends Model
{
    protected $fillable = [
        'dictionary_id',
        'title',
        'description',
    ];

    public function dictionary(): BelongsTo
    {
        return $this->belongsTo(Dictionary::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(DictionaryEntry::class, 'group_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(DictionaryGroupAttachment::class)->orderBy('sort_order')->orderBy('id');
    }

    public function displayTitle(): string
    {
        if (filled($this->title)) {
            return $this->title;
        }

        if ($this->relationLoaded('entries')) {
            $terms = $this->entries->pluck('term')->filter()->take(3)->implode(', ');

            if ($terms !== '') {
                return $terms;
            }
        }

        return $this->exists ? 'Объединение #'.$this->id : 'Объединение слов';
    }

    protected static function booted(): void
    {
        static::deleting(function (self $group) {
            $group->loadMissing('attachments');

            foreach ($group->attachments as $attachment) {
                Storage::disk($attachment->disk)->delete($attachment->path);
            }
        });
    }
}
