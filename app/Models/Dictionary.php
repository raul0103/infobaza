<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dictionary extends Model
{
    protected $fillable = ['name', 'description', 'language'];

    public function entries(): HasMany
    {
        return $this->hasMany(DictionaryEntry::class);
    }
}
