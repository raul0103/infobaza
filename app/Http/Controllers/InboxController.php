<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use App\Models\InboxItem;
use App\Models\Movie;
use App\Models\Note;
use App\Models\Topic;
use App\Services\ActivityTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InboxController extends Controller
{
    public function index(): View
    {
        return view('inbox.index', [
            'pending' => InboxItem::where('user_id', Auth::id())->whereNull('processed_at')->latest()->get(),
            'processed' => InboxItem::where('user_id', Auth::id())->whereNotNull('processed_at')->latest('processed_at')->limit(30)->get(),
            'topicGroups' => Topic::grouped(Auth::id()),
            'dictionaries' => Dictionary::where('user_id', Auth::id())->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['content' => 'required|string|max:2000']);
        InboxItem::create(['content' => $request->input('content'), 'user_id' => Auth::id()]);

        return back()->with('success', 'Сохранено в инбокс.');
    }

    public function convert(Request $request, InboxItem $inbox): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($inbox);
        $target = $request->validate([
            'target' => 'required|in:note,book,movie,word',
        ])['target'];

        return match ($target) {
            'note' => $this->convertToNote($request, $inbox),
            'book' => $this->convertToBook($request, $inbox),
            'movie' => $this->convertToMovie($request, $inbox),
            'word' => $this->convertToWord($request, $inbox),
        };
    }

    public function destroy(InboxItem $inbox): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($inbox);
        $inbox->delete();

        return back()->with('success', 'Удалено.');
    }

    private function convertToNote(Request $request, InboxItem $inbox): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'topic_id' => 'nullable|exists:topics,id',
        ]);

        $note = Note::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'content' => $inbox->content,
            'topic_id' => $data['topic_id'] ?? null,
            'visibility' => 'private',
        ]);

        $this->markProcessed($inbox, ['note_id' => $note->id]);
        ActivityTracker::log('note');

        return redirect()->route('notes.show', $note)->with('success', 'Запись создана из инбокса.');
    }

    private function convertToBook(Request $request, InboxItem $inbox): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
        ]);

        $book = Book::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'author' => $data['author'] ?? null,
            'description' => $inbox->content,
            'status' => 'queued',
            'visibility' => 'private',
        ]);

        $this->markProcessed($inbox, ['book_id' => $book->id]);

        return redirect()->route('books.show', $book)->with('success', 'Книга добавлена на очередь.');
    }

    private function convertToMovie(Request $request, InboxItem $inbox): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
        ]);

        $movie = Movie::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'director' => $data['director'] ?? null,
            'description' => $inbox->content,
            'status' => 'queued',
            'visibility' => 'private',
        ]);

        $this->markProcessed($inbox, ['movie_id' => $movie->id]);

        return redirect()->route('movies.show', $movie)->with('success', 'Фильм добавлен на очередь.');
    }

    private function convertToWord(Request $request, InboxItem $inbox): RedirectResponse
    {
        $data = $request->validate([
            'dictionary_id' => 'required|exists:dictionaries,id',
            'term' => 'required|string|max:255',
            'definition' => 'required|string',
            'example' => 'nullable|string',
        ]);

        $entry = DictionaryEntry::create([
            'user_id' => Auth::id(),
            'dictionary_id' => $data['dictionary_id'],
            'term' => $data['term'],
            'definition' => $data['definition'],
            'example' => $data['example'] ?? null,
            'visibility' => 'private',
        ]);

        $this->markProcessed($inbox, ['dictionary_entry_id' => $entry->id]);

        return redirect()->route('dictionaries.show', $entry->dictionary_id)
            ->with('success', 'Слово добавлено в словарь.');
    }

    private function markProcessed(InboxItem $inbox, array $links): void
    {
        $inbox->update(array_merge($links, ['processed_at' => now()]));
        ActivityTracker::log('inbox');
    }
}
