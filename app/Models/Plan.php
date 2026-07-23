<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = ['title', 'description', 'status'];

    public static function statusLabels(): array
    {
        return [
            'queued' => 'Хочу сделать',
            'active' => 'В работе',
            'done' => 'Сделано',
        ];
    }

    public function steps(): HasMany
    {
        return $this->hasMany(PlanStep::class)->orderBy('position')->orderBy('id');
    }

    public function progressPercent(): ?int
    {
        $total = $this->steps_count ?? $this->steps()->count();
        if ($total === 0) {
            return null;
        }

        $done = $this->steps_done_count ?? $this->steps()->where('is_done', true)->count();

        return (int) round(($done / $total) * 100);
    }
}
