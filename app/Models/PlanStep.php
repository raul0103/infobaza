<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanStep extends Model
{
    protected $fillable = ['plan_id', 'title', 'is_done', 'position'];

    protected function casts(): array
    {
        return [
            'is_done' => 'boolean',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
