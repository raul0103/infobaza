<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use App\Models\Fact;
use App\Models\FactGroup;
use App\Services\ActivityTracker;
use App\Services\ReviewBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $dictionaries = Dictionary::query()
            ->withCount('entries')
            ->has('entries')
            ->orderBy('name')
            ->get();

        return view('review.index', [
            'dictionaries' => $dictionaries,
            'totalWords' => DictionaryEntry::count(),
        ]);
    }

    public function factsIndex(): View
    {
        $groups = FactGroup::query()
            ->withCount('facts')
            ->has('facts')
            ->orderBy('name')
            ->get();

        return view('review.facts-index', [
            'groups' => $groups,
            'totalFacts' => Fact::count(),
        ]);
    }

    public function factsSession(): View
    {
        return view('review.facts', [
            'facts' => ReviewBatch::pick(Fact::query()->with('group')),
            'total' => Fact::count(),
            'badge' => 'Все факты',
            'backUrl' => route('review.facts'),
            'refreshUrl' => route('review.facts.all'),
            'answerRoute' => 'review.facts.answer',
            'showGroupBadge' => true,
            'emptyUrl' => route('facts.index'),
            'emptyLabel' => 'К фактам',
        ]);
    }

    public function factsGroupSession(FactGroup $factGroup): View
    {
        return view('review.facts', [
            'facts' => ReviewBatch::pick(
                Fact::query()->where('fact_group_id', $factGroup->id)
            ),
            'total' => $factGroup->facts()->count(),
            'badge' => $factGroup->name,
            'backUrl' => route('review.facts'),
            'refreshUrl' => route('review.facts.group', $factGroup),
            'answerRoute' => 'review.facts.answer',
            'showGroupBadge' => false,
            'emptyUrl' => route('facts.index'),
            'emptyLabel' => 'К фактам',
        ]);
    }

    public function factsAnswer(Request $request, Fact $fact): RedirectResponse|JsonResponse
    {
        $fact->recordReview(true);
        ActivityTracker::log('card');

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('review.facts.all');
    }

    public function allSession(): View
    {
        return view('review.all', [
            'entries' => ReviewBatch::pick(DictionaryEntry::query()->with('dictionary')),
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
            'entries' => ReviewBatch::pick(
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
}
