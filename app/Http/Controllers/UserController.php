<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.$search.'%';
                $query->where(function ($nested) use ($like) {
                    $nested->where('username', 'like', $like)
                        ->orWhere('name', 'like', $like);
                });
            })
            ->withCount([
                'books as public_books_count' => fn ($q) => $q->where('visibility', 'public'),
                'movies as public_movies_count' => fn ($q) => $q->where('visibility', 'public'),
                'notes as public_notes_count' => fn ($q) => $q->where('visibility', 'public'),
                'quotes as public_quotes_count' => fn ($q) => $q->where('visibility', 'public'),
                'dictionaries as public_dictionaries_count' => fn ($q) => $q->where('visibility', 'public'),
                'dictionaryEntries as public_words_count' => fn ($q) => $q->where('visibility', 'public'),
                'topics as public_topics_count' => fn ($q) => $q->where('visibility', 'public'),
                'events as public_events_count' => fn ($q) => $q->where('visibility', 'public'),
                'reminders as public_reminders_count' => fn ($q) => $q->where('visibility', 'public'),
                'journalEntries as public_journal_count' => fn ($q) => $q->where('visibility', 'public'),
            ])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function show(User $user): View
    {
        $publicBooks = $user->books()
            ->where('visibility', 'public')
            ->withCount('quotes')
            ->latest()
            ->limit(8)
            ->get();

        $publicNotes = $user->notes()
            ->where('visibility', 'public')
            ->latest()
            ->limit(8)
            ->get();

        $publicQuotes = $user->quotes()
            ->where('visibility', 'public')
            ->with(['book', 'movie'])
            ->latest()
            ->limit(8)
            ->get();

        $publicMovies = $user->movies()
            ->where('visibility', 'public')
            ->withCount('quotes')
            ->latest()
            ->limit(8)
            ->get();

        $publicDictionaries = $user->dictionaries()
            ->where('visibility', 'public')
            ->withCount('entries')
            ->latest()
            ->limit(8)
            ->get();

        $publicWords = $user->dictionaryEntries()
            ->where('visibility', 'public')
            ->with('dictionary')
            ->latest()
            ->limit(8)
            ->get();

        $publicTopics = $user->topics()
            ->where('visibility', 'public')
            ->latest()
            ->limit(8)
            ->get();

        $stats = [
            'books' => $user->books()->count(),
            'movies' => $user->movies()->count(),
            'notes' => $user->notes()->count(),
            'quotes' => $user->quotes()->count(),
            'dictionaries' => $user->dictionaries()->count(),
            'words' => $user->dictionaryEntries()->count(),
            'topics' => $user->topics()->count(),
            'events' => $user->events()->count(),
            'reminders' => $user->reminders()->count(),
            'journal' => $user->journalEntries()->count(),
            'note_words' => (int) $user->notes()->selectRaw('COALESCE(SUM(LENGTH(content)), 0) as total')->value('total'),
        ];

        $hasPublicContent = collect([
            $publicBooks,
            $publicMovies,
            $publicNotes,
            $publicQuotes,
            $publicDictionaries,
            $publicWords,
            $publicTopics,
        ])->contains(fn ($collection) => $collection->isNotEmpty());

        return view('users.show', compact(
            'user',
            'publicBooks',
            'publicMovies',
            'publicNotes',
            'publicQuotes',
            'publicDictionaries',
            'publicWords',
            'publicTopics',
            'stats',
            'hasPublicContent',
        ));
    }
}
