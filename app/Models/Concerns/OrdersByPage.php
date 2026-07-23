<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait OrdersByPage
{
    /** Страницы по возрастанию; без номера — в конце. */
    public function scopeOrderByPageAsc(Builder $query): Builder
    {
        return $query
            ->orderByRaw("CASE WHEN page IS NULL OR TRIM(page) = '' THEN 1 ELSE 0 END")
            ->orderByRaw('CAST(page AS UNSIGNED)')
            ->orderBy('page');
    }
}
