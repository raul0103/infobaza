<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use App\Models\Fact;
use App\Services\ActivityTracker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReviewController extends Controller
{
    private const BATCH_SIZE = 9;

    public function index(): RedirectResponse
    {
        return redirect()->route('dictionaries.index');
    }

    public function factsSession(Request $request): View
    {
        $query = Fact::due()->orderBy('next_review_at');

        if ($request->filled('exclude')) {
            $query->where('id', '!=', $request->integer('exclude'));
        }

        $fact = $query->first()
            ?? Fact::inRandomOrder()->first();

        return view('review.facts', [
            'fact' => $fact,
            'total' => Fact::count(),
        ]);
    }

    public function factsAnswer(Fact $fact): RedirectResponse
    {
        $fact->recordReview(true);
        ActivityTracker::log('card');

        return redirect()->route('review.facts', ['exclude' => $fact->id]);
    }

    public function allSession(): View
    {
        return view('review.all', [
            'entries' => $this->pickBatch(DictionaryEntry::query()->with('dictionary')),
            'total' => DictionaryEntry::count(),
        ]);
    }

    public function allAnswer(Request $request, DictionaryEntry $entry): RedirectResponse|JsonResponse
    {
        $entry->recordReview(true);
        ActivityTracker::log('card');

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('review.all');
    }

    public function session(Dictionary $dictionary): View
    {
        return view('review.session', [
            'dictionary' => $dictionary,
            'entries' => $this->pickBatch(
                DictionaryEntry::query()->where('dictionary_id', $dictionary->id)
            ),
            'total' => $dictionary->entries()->count(),
        ]);
    }

    public function answer(Request $request, Dictionary $dictionary, DictionaryEntry $entry): RedirectResponse|JsonResponse
    {
        abort_unless($entry->dictionary_id === $dictionary->id, 404);

        $entry->recordReview(true);
        ActivityTracker::log('card');

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('review.session', $dictionary);
    }

    /**
     * @param  Builder<DictionaryEntry>  $query
     * @return Collection<int, DictionaryEntry>
     */
    private function pickBatch(Builder $query): Collection
    {
        $entries = (clone $query)
            ->due()
            ->orderBy('next_review_at')
            ->limit(self::BATCH_SIZE)
            ->get();

        if ($entries->count() >= self::BATCH_SIZE) {
            return $entries;
        }

        $needed = self::BATCH_SIZE - $entries->count();
        $excludeIds = $entries->pluck('id')->all();

        $extra = (clone $query)
            ->when($excludeIds !== [], fn (Builder $q) => $q->whereNotIn('id', $excludeIds))
            ->inRandomOrder()
            ->limit($needed)
            ->get();

        return $entries->concat($extra)->values();
    }
}
