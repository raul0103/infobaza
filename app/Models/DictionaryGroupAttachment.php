<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DictionaryGroupAttachment extends Model
{
    protected $fillable = [
        'dictionary_entry_group_id',
        'disk',
        'path',
        'original_name',
        'mime',
        'size',
        'sort_order',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(DictionaryEntryGroup::class, 'dictionary_entry_group_id');
    }

    public function isImage(): bool
    {
        return is_string($this->mime) && str_starts_with($this->mime, 'image/');
    }

    public function url(): string
    {
        return asset('storage/'.$this->path);
    }

    protected static function booted(): void
    {
        static::deleting(function (self $attachment) {
            Storage::disk($attachment->disk)->delete($attachment->path);
        });
    }
}
