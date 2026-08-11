<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use App\Models\Fact;
use App\Models\FactGroup;
use App\Models\Movie;
use App\Models\Phrase;
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

    public function phrasesIndex(): View
    {
        $books = Book::query()
            ->withCount('phrases')
            ->has('phrases')
            ->orderBy('title')
            ->get();

        $movies = Movie::query()
            ->withCount('phrases')
            ->has('phrases')
            ->orderBy('title')
            ->get();

        return view('review.phrases-index', [
            'books' => $books,
            'movies' => $movies,
            'totalPhrases' => Phrase::count(),
        ]);
    }

    public function phrasesSession(): View
    {
        return view('review.phrases', [
            'phrases' => ReviewBatch::pick(Phrase::query()->with(['book', 'movie'])),
            'total' => Phrase::count(),
            'badge' => 'Все обороты',
            'backUrl' => route('review.phrases'),
            'refreshUrl' => route('review.phrases.all'),
            'answerRoute' => 'review.phrases.answer',
            'showSourceBadge' => true,
            'emptyUrl' => route('phrases.index'),
            'emptyLabel' => 'К оборотам',
        ]);
    }

    public function phrasesBookSession(Book $book): View
    {
        return view('review.phrases', [
            'phrases' => ReviewBatch::pick(
                Phrase::query()->where('book_id', $book->id)->with(['book', 'movie'])
            ),
            'total' => $book->phrases()->count(),
            'badge' => $book->title,
            'backUrl' => route('review.phrases'),
            'refreshUrl' => route('review.phrases.book', $book),
            'answerRoute' => 'review.phrases.answer',
            'showSourceBadge' => false,
            'emptyUrl' => route('phrases.index'),
            'emptyLabel' => 'К оборотам',
        ]);
    }

    public function phrasesMovieSession(Movie $movie): View
    {
        return view('review.phrases', [
            'phrases' => ReviewBatch::pick(
                Phrase::query()->where('movie_id', $movie->id)->with(['book', 'movie'])
            ),
            'total' => $movie->phrases()->count(),
            'badge' => $movie->title,
            'backUrl' => route('review.phrases'),
            'refreshUrl' => route('review.phrases.movie', $movie),
            'answerRoute' => 'review.phrases.answer',
            'showSourceBadge' => false,
            'emptyUrl' => route('phrases.index'),
            'emptyLabel' => 'К оборотам',
        ]);
    }

    public function phrasesAnswer(Request $request, Phrase $phrase): RedirectResponse|JsonResponse
    {
        $phrase->recordReview(true);
        ActivityTracker::log('card');

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('review.phrases.all');
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
