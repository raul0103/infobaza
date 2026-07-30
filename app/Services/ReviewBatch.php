<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ReviewBatch
{
    public const SIZE = 9;

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Collection<int, \Illuminate\Database\Eloquent\Model>
     */
    public static function pick(Builder $query, int $size = self::SIZE): Collection
    {
        $items = (clone $query)
            ->due()
            ->orderBy('next_review_at')
            ->limit($size)
            ->get();

        if ($items->count() >= $size) {
            return $items;
        }

        $needed = $size - $items->count();
        $excludeIds = $items->pluck('id')->all();

        $extra = (clone $query)
            ->when($excludeIds !== [], fn (Builder $q) => $q->whereNotIn('id', $excludeIds))
            ->inRandomOrder()
            ->limit($needed)
            ->get();

        return $items->concat($extra)->values();
    }
}
