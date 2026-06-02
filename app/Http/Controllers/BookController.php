<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\ActivityTracker;
use App\Support\CurrentUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::where('user_id', Auth::id())->withCount('quotes')->orderBy('title')->get()->groupBy('status');

        return view('books.index', [
            'sections' => collect(Book::statusLabels())->map(fn ($label, $status) => [
                'status' => $status,
                'label' => $label,
                'books' => $books->get($status, collect()),
            ])->values(),
        ]);
    }

    public function create(): View
    {
        return view('books.form', ['book' => new Book]);
    }

    public function store(Request $request): RedirectResponse
    {
        $book = Book::create($this->validated($request) + ['user_id' => CurrentUser::id()]);

        return redirect()->route('books.show', $book)->with('success', 'Книга добавлена.');
    }

    public function show(Book $book): View
    {
        $this->ensureOwnedByCurrentUser($book);
        $book->load(['quotes' => fn ($q) => $q->latest()]);

        return view('books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        $this->ensureOwnedByCurrentUser($book);
        return view('books.form', compact('book'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($book);
        $data = $this->validated($request);
        $pagesAdded = (int) ($data['pages_added'] ?? 0);
        unset($data['pages_added']);

        if ($pagesAdded > 0) {
            $data['current_page'] = ($data['current_page'] ?? $book->current_page ?? 0) + $pagesAdded;
        }

        $book->update($data);

        if ($pagesAdded > 0) {
            ActivityTracker::log('pages', $pagesAdded);
        }

        return redirect()->route('books.show', $book)->with('success', 'Книга обновлена.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($book);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Книга удалена.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1|max:2100',
            'description' => 'nullable|string',
            'review_takeaway' => 'nullable|string',
            'status' => 'required|in:queued,reading,finished',
            'current_page' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:1',
            'started_at' => 'nullable|date',
            'finished_at' => 'nullable|date',
            'pages_added' => 'nullable|integer|min:0',
            'visibility' => 'required|in:private,public',
        ]);
    }
}
