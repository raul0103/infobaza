<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Memo extends Model
{
    protected $fillable = ['name', 'description'];

    public function entries(): HasMany
    {
        return $this->hasMany(MemoEntry::class);
    }
}
