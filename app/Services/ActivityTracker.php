<?php

namespace App\Services;

use App\Models\DailyActivity;
use Carbon\Carbon;

class ActivityTracker
{
    public static function log(string $type, int $amount = 1): void
    {
        $field = match ($type) {
            'note' => 'notes_count',
            'card' => 'cards_reviewed',
            'quote' => 'quotes_count',
            'pages' => 'pages_read',
            'inbox' => 'inbox_processed',
            default => null,
        };

        if (! $field) {
            return;
        }

        $activity = DailyActivity::firstOrCreate(['date' => today()]);
        $activity->increment($field, $amount);
    }

    public static function streak(): int
    {
        $streak = 0;
        $date = today();

        while (self::hadActivityOn($date)) {
            $streak++;
            $date = $date->copy()->subDay();
        }

        return $streak;
    }

    public static function hadActivityOn(Carbon $date): bool
    {
        $activity = DailyActivity::whereDate('date', $date)->first();

        if (! $activity) {
            return false;
        }

        return ($activity->notes_count + $activity->cards_reviewed
            + $activity->quotes_count + $activity->pages_read + $activity->inbox_processed) > 0;
    }

    public static function todayActivity(): DailyActivity
    {
        return DailyActivity::firstOrCreate(['date' => today()]);
    }

    public static function studiedToday(): bool
    {
        return self::hadActivityOn(today());
    }
}
