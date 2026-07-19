<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookThought extends Model
{
    protected $fillable = [
        'book_id',
        'content',
        'chapter',
        'page',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return ['is_favorite' => 'boolean'];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
