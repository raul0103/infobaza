<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use App\Models\NoteQuestion;
use App\Services\ActivityTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $dictionaries = Dictionary::withCount([
            'entries',
            'entries as due_entries_count' => fn ($q) => $q->due(),
        ])->whereHas('entries')->orderBy('name')->get();

        return view('review.index', [
            'dictionaries' => $dictionaries,
            'dueQuestions' => NoteQuestion::due()->count(),
            'totalDueCards' => DictionaryEntry::due()->count(),
        ]);
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
            'due' => $dictionary->entries()->due()->count(),
            'total' => $dictionary->entries()->count(),
        ]);
    }

    public function answer(Request $request, Dictionary $dictionary, DictionaryEntry $entry): RedirectResponse
    {
        $request->validate(['known' => 'required|boolean']);
        $entry->recordReview($request->boolean('known'));
        ActivityTracker::log('card');

        return redirect()->route('review.session', [
            'dictionary' => $dictionary,
            'exclude' => $entry->id,
        ]);
    }
}
