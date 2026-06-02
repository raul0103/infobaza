<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['entry_date', 'title', 'content', 'mood'];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
        ];
    }
}
