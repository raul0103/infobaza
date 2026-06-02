<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'year', 'description', 'status',
        'current_page', 'total_pages', 'started_at', 'finished_at', 'review_takeaway',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'finished_at' => 'date',
        ];
    }

    public static function statusLabels(): array
    {
        return [
            'queued' => 'На очереди',
            'reading' => 'Читаю',
            'finished' => 'Прочитано',
        ];
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function readingPercent(): ?int
    {
        if (! $this->total_pages || $this->current_page === null) {
            return null;
        }

        return min(100, (int) round(($this->current_page / $this->total_pages) * 100));
    }
}
