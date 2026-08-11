<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Movie;
use App\Models\Phrase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhraseController extends Controller
{
    public function index(): View
    {
        $phrases = Phrase::query()
            ->with(['book', 'movie'])
            ->orderByDesc('is_favorite')
            ->orderByPageAsc()
            ->latest()
            ->get();

        $bookSources = $phrases
            ->filter(fn (Phrase $phrase) => $phrase->book_id)
            ->groupBy('book_id')
            ->map(function ($items) {
                /** @var \Illuminate\Support\Collection<int, Phrase> $items */
                $book = $items->first()->book;

                return (object) [
                    'id' => 'book-'.$book->id,
                    'label' => $book->title,
                    'phrases' => $items->values(),
                ];
            })
            ->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        $movieSources = $phrases
            ->filter(fn (Phrase $phrase) => $phrase->movie_id)
            ->groupBy('movie_id')
            ->map(function ($items) {
                /** @var \Illuminate\Support\Collection<int, Phrase> $items */
                $movie = $items->first()->movie;

                return (object) [
                    'id' => 'movie-'.$movie->id,
                    'label' => $movie->title,
                    'phrases' => $items->values(),
                ];
            })
            ->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        $ungrouped = $phrases
            ->filter(fn (Phrase $phrase) => ! $phrase->book_id && ! $phrase->movie_id)
            ->values();

        return view('phrases.index', [
            'bookSources' => $bookSources,
            'movieSources' => $movieSources,
            'ungroupedPhrases' => $ungrouped,
            'totalPhrases' => $phrases->count(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('phrases.form', [
            'phrase' => new Phrase([
                'book_id' => $request->book_id,
                'movie_id' => $request->movie_id,
            ]),
            'books' => Book::orderBy('title')->get(),
            'movies' => Movie::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $phrase = Phrase::create($this->validated($request));

        return $this->redirectAfterSave($phrase)->with('success', 'Оборот речи сохранён.');
    }

    public function edit(Phrase $phrase): View
    {
        return view('phrases.form', [
            'phrase' => $phrase,
            'books' => Book::orderBy('title')->get(),
            'movies' => Movie::orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Phrase $phrase): RedirectResponse
    {
        $phrase->update($this->validated($request));

        return $this->redirectAfterSave($phrase)->with('success', 'Оборот речи обновлён.');
    }

    public function destroy(Phrase $phrase): RedirectResponse
    {
        $bookId = $phrase->book_id;
        $movieId = $phrase->movie_id;
        $phrase->delete();

        if ($bookId) {
            return redirect()->route('books.show', $bookId)->with('success', 'Оборот речи удалён.');
        }
        if ($movieId) {
            return redirect()->route('movies.show', $movieId)->with('success', 'Оборот речи удалён.');
        }

        return redirect()->route('phrases.index')->with('success', 'Оборот речи удалён.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'book_id' => 'nullable|exists:books,id',
            'movie_id' => 'nullable|exists:movies,id',
            'text' => 'required|string',
            'note' => 'nullable|string',
            'page' => 'nullable|string|max:50',
            'character' => 'nullable|string|max:255',
        ]);

        if (! empty($data['book_id'])) {
            $data['movie_id'] = null;
        } elseif (! empty($data['movie_id'])) {
            $data['book_id'] = null;
        }

        return $data;
    }

    private function redirectAfterSave(Phrase $phrase): RedirectResponse
    {
        if ($phrase->book_id) {
            return redirect()->route('books.show', $phrase->book_id);
        }
        if ($phrase->movie_id) {
            return redirect()->route('movies.show', $phrase->movie_id);
        }

        return redirect()->route('phrases.index');
    }
}
