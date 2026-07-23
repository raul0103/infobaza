<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tip extends Model
{
    protected $fillable = [
        'book_id',
        'movie_id',
        'title',
        'content',
        'chapter',
        'page',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function displayTitle(): string
    {
        if (filled($this->title)) {
            return $this->title;
        }

        return str($this->content)->limit(60)->toString();
    }
}
