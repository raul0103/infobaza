<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Movie;
use App\Models\Tip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipController extends Controller
{
    public function create(Request $request): View
    {
        return view('tips.form', [
            'tip' => new Tip([
                'book_id' => $request->book_id,
                'movie_id' => $request->movie_id,
            ]),
            'books' => Book::orderBy('title')->get(),
            'movies' => Movie::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tip = Tip::create($this->validated($request));

        return $this->redirectAfterSave($tip)->with('success', 'Приём сохранён.');
    }

    public function edit(Tip $tip): View
    {
        return view('tips.form', [
            'tip' => $tip,
            'books' => Book::orderBy('title')->get(),
            'movies' => Movie::orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Tip $tip): RedirectResponse
    {
        $tip->update($this->validated($request));

        return $this->redirectAfterSave($tip)->with('success', 'Приём обновлён.');
    }

    public function destroy(Tip $tip): RedirectResponse
    {
        $bookId = $tip->book_id;
        $movieId = $tip->movie_id;
        $tip->delete();

        if ($bookId) {
            return redirect()->route('books.show', $bookId)->with('success', 'Приём удалён.');
        }
        if ($movieId) {
            return redirect()->route('movies.show', $movieId)->with('success', 'Приём удалён.');
        }

        return redirect()->route('dashboard')->with('success', 'Приём удалён.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'book_id' => 'nullable|exists:books,id',
            'movie_id' => 'nullable|exists:movies,id',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'chapter' => 'nullable|string|max:255',
            'page' => 'nullable|string|max:50',
        ]);

        if (! empty($data['book_id'])) {
            $data['movie_id'] = null;
        } elseif (! empty($data['movie_id'])) {
            $data['book_id'] = null;
        }

        return $data;
    }

    private function redirectAfterSave(Tip $tip): RedirectResponse
    {
        if ($tip->book_id) {
            return redirect()->route('books.show', $tip->book_id);
        }
        if ($tip->movie_id) {
            return redirect()->route('movies.show', $tip->movie_id);
        }

        return redirect()->route('dashboard');
    }
}
