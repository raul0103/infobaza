<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use App\Models\Fact;
use App\Services\ActivityTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $dictionaries = Dictionary::withCount('entries')
            ->whereHas('entries')
            ->orderBy('name')
            ->get();

        return view('review.index', [
            'dictionaries' => $dictionaries,
            'totalFacts' => Fact::count(),
        ]);
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

    public function session(Request $request, Dictionary $dictionary): View
    {
        $query = $dictionary->entries()->due()->orderBy('next_review_at');

        if ($request->filled('exclude')) {
            $query->where('id', '!=', $request->integer('exclude'));
        }

        $entry = $query->first()
            ?? $dictionary->entries()->inRandomOrder()->first();

        return view('review.session', [
            'dictionary' => $dictionary,
            'entry' => $entry,
            'total' => $dictionary->entries()->count(),
        ]);
    }

    public function answer(Dictionary $dictionary, DictionaryEntry $entry): RedirectResponse
    {
        $entry->recordReview(true);
        ActivityTracker::log('card');

        return redirect()->route('review.session', [
            'dictionary' => $dictionary,
            'exclude' => $entry->id,
        ]);
    }
}
