<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = ['title', 'director', 'year', 'description', 'status'];

    public static function statusLabels(): array
    {
        return [
            'queued' => 'Хочу посмотреть',
            'watching' => 'Смотрю',
            'watched' => 'Просмотрено',
        ];
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function tips(): HasMany
    {
        return $this->hasMany(Tip::class);
    }

    public function thoughts(): HasMany
    {
        return $this->hasMany(BookThought::class);
    }

    public function phrases(): HasMany
    {
        return $this->hasMany(Phrase::class);
    }
}
