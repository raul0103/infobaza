<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\ActivityTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::withCount(['quotes', 'thoughts'])->orderBy('title')->get()->groupBy('status');

        return view('books.index', [
            'sections' => collect(Book::statusLabels())->map(function ($label, $status) use ($books) {
                $sectionBooks = $books->get($status, collect());

                if ($status === 'queued') {
                    $sectionBooks = $sectionBooks
                        ->sortBy(fn (Book $book) => [(int) $book->priority, mb_strtolower($book->title)])
                        ->values();
                }

                return [
                    'status' => $status,
                    'label' => $label,
                    'books' => $sectionBooks,
                ];
            })->values(),
        ]);
    }

    public function create(): View
    {
        return view('books.form', ['book' => new Book(['status' => 'queued'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($data['status'] === 'queued') {
            $data['priority'] = ((int) Book::where('status', 'queued')->max('priority')) + 1;
        }

        $book = Book::create($data);

        return redirect()->route('books.show', $book)->with('success', 'Книга добавлена.');
    }

    public function show(Request $request, Book $book): View
    {
        $q = trim((string) $request->query('q', ''));

        $book->load([
            'quotes' => function ($query) use ($q) {
                $query->orderByPageAsc()->orderByDesc('is_favorite')->latest();
                if ($q !== '') {
                    $query->where(function ($inner) use ($q) {
                        $inner->where('text', 'like', "%{$q}%")
                            ->orWhere('character', 'like', "%{$q}%")
                            ->orWhere('context', 'like', "%{$q}%")
                            ->orWhere('page', 'like', "%{$q}%");
                    });
                }
            },
            'thoughts' => fn ($query) => $query->orderByPageAsc()->orderByDesc('is_favorite')->latest(),
            'tips' => fn ($query) => $query->orderByPageAsc()->latest(),
        ]);

        return view('books.show', [
            'book' => $book,
            'q' => $q,
            'quotesTotal' => $book->quotes()->count(),
        ]);
    }

    public function edit(Book $book): View
    {
        return view('books.form', compact('book'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $this->validated($request);
        $pagesAdded = (int) ($data['pages_added'] ?? 0);
        unset($data['pages_added']);

        if ($data['status'] === 'queued' && $book->status !== 'queued') {
            $data['priority'] = ((int) Book::where('status', 'queued')->max('priority')) + 1;
        }

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
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Книга удалена.');
    }

    public function updateProgress(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'current_page' => 'required|integer|min:0',
        ]);

        $newPage = (int) $data['current_page'];
        if ($book->total_pages) {
            $newPage = min($newPage, (int) $book->total_pages);
        }

        $oldPage = (int) ($book->current_page ?? 0);
        $delta = $newPage - $oldPage;

        $updates = ['current_page' => $newPage];

        if ($book->total_pages && $newPage >= (int) $book->total_pages) {
            $updates['status'] = 'finished';
            if (! $book->finished_at) {
                $updates['finished_at'] = now()->toDateString();
            }
        } elseif ($newPage > 0 && in_array($book->status, ['queued', 'finished'], true)) {
            $updates['status'] = 'reading';
            $updates['finished_at'] = null;
            if (! $book->started_at) {
                $updates['started_at'] = now()->toDateString();
            }
        }

        $book->update($updates);

        if ($delta > 0) {
            ActivityTracker::log('pages', $delta);
        }

        return redirect()->route('books.show', $book)->with('success', 'Прогресс обновлён.');
    }

    public function reorderQueued(Request $request): JsonResponse
    {
        $data = $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'required|integer|distinct|exists:books,id',
        ]);

        $bookIds = collect($data['book_ids'])->map(fn ($id) => (int) $id)->values();
        $queuedCount = Book::whereIn('id', $bookIds)
            ->where('status', 'queued')
            ->count();

        if ($queuedCount !== $bookIds->count()) {
            return response()->json(['message' => 'Можно сортировать только книги из списка «Хочу прочитать».'], 422);
        }

        DB::transaction(function () use ($bookIds) {
            foreach ($bookIds as $index => $bookId) {
                Book::whereKey($bookId)->update(['priority' => $index + 1]);
            }
        });

        return response()->json(['message' => 'Приоритет сохранён.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1|max:2100',
            'description' => 'nullable|string',
            'status' => 'required|in:queued,reading,finished',
            'current_page' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:1',
            'started_at' => 'nullable|date',
            'finished_at' => 'nullable|date',
            'pages_added' => 'nullable|integer|min:0',
        ]);
    }
}
