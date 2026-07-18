<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookThought;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookThoughtController extends Controller
{
    public function create(Book $book): View
    {
        return view('books.thoughts.form', [
            'book' => $book,
            'thought' => new BookThought,
        ]);
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        $book->thoughts()->create($this->validated($request));

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Мысль о книге сохранена.');
    }

    public function edit(Book $book, BookThought $thought): View
    {
        $this->ensureThoughtBelongsToBook($book, $thought);

        return view('books.thoughts.form', compact('book', 'thought'));
    }

    public function update(Request $request, Book $book, BookThought $thought): RedirectResponse
    {
        $this->ensureThoughtBelongsToBook($book, $thought);
        $thought->update($this->validated($request));

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Мысль обновлена.');
    }

    public function destroy(Book $book, BookThought $thought): RedirectResponse
    {
        $this->ensureThoughtBelongsToBook($book, $thought);
        $thought->delete();

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Мысль удалена.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'content' => 'required|string',
            'chapter' => 'nullable|string|max:255',
            'page' => 'nullable|string|max:50',
        ]);
    }

    private function ensureThoughtBelongsToBook(Book $book, BookThought $thought): void
    {
        abort_unless($thought->book_id === $book->id, 404);
    }
}
