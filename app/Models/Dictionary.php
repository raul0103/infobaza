<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dictionary extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'language', 'visibility'];

    public function entries(): HasMany
    {
        return $this->hasMany(DictionaryEntry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
