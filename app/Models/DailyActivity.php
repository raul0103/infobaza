<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyActivity extends Model
{
    protected $fillable = [
        'date', 'notes_count', 'cards_reviewed', 'journal_count',
        'quotes_count', 'pages_read', 'inbox_processed',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }
}
