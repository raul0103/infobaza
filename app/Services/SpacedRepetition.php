<?php

namespace App\Services;

use Carbon\Carbon;

class SpacedRepetition
{
    public static function scheduleReview(object $model, bool $known): void
    {
        $interval = $model->interval_days ?? 1;
        $reviews = ($model->review_count ?? 0) + 1;

        if ($known) {
            $interval = match (true) {
                $reviews <= 1 => 1,
                $reviews === 2 => 3,
                $reviews === 3 => 7,
                $reviews === 4 => 14,
                default => min(60, (int) round($interval * 1.8)),
            };
        } else {
            $interval = 1;
        }

        $model->update([
            'interval_days' => $interval,
            'next_review_at' => now()->addDays($interval),
            'review_count' => $reviews,
        ]);
    }

    public static function isDue(?Carbon $nextReviewAt): bool
    {
        return $nextReviewAt === null || $nextReviewAt->lte(now());
    }
}
