<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemoEntry extends Model
{
    protected $fillable = ['memo_id', 'title', 'content'];

    public function memo(): BelongsTo
    {
        return $this->belongsTo(Memo::class);
    }

    public function scopeSearch(Builder $query, string $q): Builder
    {
        $q = trim($q);
        if ($q === '') {
            return $query;
        }

        return $query->where(function (Builder $inner) use ($q) {
            $inner->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%");
        });
    }
}
