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
use Illuminate\View\View;

class InboxController extends Controller
{
    public function index(): View
    {
        return view('inbox.index', [
            'pending' => InboxItem::whereNull('processed_at')->latest()->get(),
            'processed' => InboxItem::whereNotNull('processed_at')->latest('processed_at')->limit(30)->get(),
            'topicGroups' => Topic::grouped(),
            'dictionaries' => Dictionary::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['content' => 'required|string|max:2000']);
        InboxItem::create(['content' => $request->input('content')]);

        return back()->with('success', 'Сохранено в инбокс.');
    }

    public function convert(Request $request, InboxItem $inbox): RedirectResponse
    {
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
            'title' => $data['title'],
            'content' => $inbox->content,
            'topic_id' => $data['topic_id'] ?? null,
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
            'title' => $data['title'],
            'author' => $data['author'] ?? null,
            'description' => $inbox->content,
            'status' => 'queued',
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
            'title' => $data['title'],
            'director' => $data['director'] ?? null,
            'description' => $inbox->content,
            'status' => 'queued',
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
            'dictionary_id' => $data['dictionary_id'],
            'term' => $data['term'],
            'definition' => $data['definition'],
            'example' => $data['example'] ?? null,
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
