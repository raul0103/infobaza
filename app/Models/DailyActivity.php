<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyActivity extends Model
{
    protected $fillable = [
        'date', 'notes_count', 'cards_reviewed',
        'quotes_count', 'pages_read',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }
}
