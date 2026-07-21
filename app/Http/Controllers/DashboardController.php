<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\DictionaryEntry;
use App\Models\Note;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard', [
            'recentNotes' => Note::with('topic.parent')->latest()->limit(5)->get(),
            'readingBooks' => Book::where('status', 'reading')->orderBy('title')->limit(3)->get(),
            'randomQuote' => $this->pickQuote($request),
            'randomWords' => $this->pickWords($request),
        ]);
    }

    private function pickQuote(Request $request): ?Quote
    {
        if ($request->filled('keep_quote') && ! $request->boolean('refresh_quote')) {
            $kept = Quote::with(['book', 'movie'])->find($request->integer('keep_quote'));
            if ($kept) {
                return $kept;
            }
        }

        $query = Quote::query()->with(['book', 'movie']);
        if ($request->filled('exclude_quote')) {
            $query->where('id', '!=', $request->integer('exclude_quote'));
        }

        return $query->inRandomOrder()->first()
            ?? Quote::with(['book', 'movie'])->inRandomOrder()->first();
    }

    private function pickWords(Request $request): Collection
    {
        if ($request->filled('keep_words') && ! $request->boolean('refresh_words')) {
            $ids = $this->parseIds($request->query('keep_words'));
            if ($ids !== []) {
                $kept = DictionaryEntry::with('dictionary')
                    ->whereIn('id', $ids)
                    ->get()
                    ->sortBy(fn (DictionaryEntry $entry) => array_search($entry->id, $ids, true))
                    ->values();

                if ($kept->isNotEmpty()) {
                    return $kept;
                }
            }
        }

        $query = DictionaryEntry::query()->with('dictionary');
        $excludeIds = $this->parseIds($request->query('exclude_words'));
        if ($excludeIds !== []) {
            $query->whereNotIn('id', $excludeIds);
        }

        $words = $query->inRandomOrder()->limit(3)->get();

        if ($words->count() < 3 && $excludeIds !== []) {
            $words = DictionaryEntry::with('dictionary')->inRandomOrder()->limit(3)->get();
        }

        return $words;
    }

    private function parseIds(mixed $value): array
    {
        return collect(explode(',', (string) $value))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
