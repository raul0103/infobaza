<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = ['user_id', 'title', 'director', 'year', 'description', 'status', 'visibility'];

    public static function statusLabels(): array
    {
        return [
            'queued' => 'На очереди',
            'watching' => 'Смотрю',
            'watched' => 'Просмотрено',
        ];
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
