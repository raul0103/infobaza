<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Movie;
use App\Models\Quote;
use App\Services\ActivityTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function index(Request $request): View
    {
        $query = Quote::with(['book', 'movie'])->orderByDesc('is_favorite')->latest();

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }
        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        return view('quotes.index', [
            'quotes' => $query->paginate(20)->withQueryString(),
            'books' => Book::orderBy('title')->get(),
            'movies' => Movie::orderBy('title')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('quotes.form', [
            'quote' => new Quote([
                'book_id' => $request->book_id,
                'movie_id' => $request->movie_id,
            ]),
            'books' => Book::orderBy('title')->get(),
            'movies' => Movie::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $quote = Quote::create($this->validated($request));
        ActivityTracker::log('quote');

        return $this->redirectAfterSave($quote)->with('success', 'Цитата сохранена.');
    }

    public function edit(Quote $quote): View
    {
        return view('quotes.form', [
            'quote' => $quote,
            'books' => Book::orderBy('title')->get(),
            'movies' => Movie::orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Quote $quote): RedirectResponse
    {
        $quote->update($this->validated($request));

        return $this->redirectAfterSave($quote)->with('success', 'Цитата обновлена.');
    }

    public function destroy(Quote $quote): RedirectResponse
    {
        $bookId = $quote->book_id;
        $movieId = $quote->movie_id;
        $quote->delete();

        if ($bookId) {
            return redirect()->route('books.show', $bookId)->with('success', 'Цитата удалена.');
        }
        if ($movieId) {
            return redirect()->route('movies.show', $movieId)->with('success', 'Цитата удалена.');
        }

        return redirect()->route('quotes.index')->with('success', 'Цитата удалена.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'book_id' => 'nullable|exists:books,id',
            'movie_id' => 'nullable|exists:movies,id',
            'text' => 'required|string',
            'page' => 'nullable|string|max:50',
            'character' => 'nullable|string|max:255',
            'context' => 'nullable|string',
        ]);

        if (! empty($data['book_id'])) {
            $data['movie_id'] = null;
        } elseif (! empty($data['movie_id'])) {
            $data['book_id'] = null;
        }

        return $data;
    }

    private function redirectAfterSave(Quote $quote): RedirectResponse
    {
        if ($quote->book_id) {
            return redirect()->route('books.show', $quote->book_id);
        }
        if ($quote->movie_id) {
            return redirect()->route('movies.show', $quote->movie_id);
        }

        return redirect()->route('quotes.index');
    }
}
