<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Topic;
use App\Services\ActivityTracker;
use App\Support\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $query = Note::where('user_id', Auth::id())->with('topic.parent')->latest();

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }
        if ($request->filled('q')) {
            $q = '%'.$request->q.'%';
            $query->where(fn ($b) => $b->where('title', 'like', $q)->orWhere('content', 'like', $q));
        }

        return view('notes.index', [
            'notes' => $query->paginate(20)->withQueryString(),
            'topicGroups' => Topic::grouped(Auth::id()),
            'filters' => $request->only(['topic_id', 'q']),
        ]);
    }

    public function create(Request $request): View
    {
        return view('notes.form', [
            'note' => new Note(['topic_id' => $request->topic_id]),
            'topicGroups' => Topic::grouped(Auth::id()),
            'allNotes' => Note::where('user_id', Auth::id())->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $note = Note::create($this->validated($request) + ['user_id' => CurrentUser::id()]);
        $this->syncLinks($note, $request);
        ActivityTracker::log('note');

        return redirect()->route('notes.show', $note)->with('success', 'Запись сохранена.');
    }

    public function show(Note $note): View
    {
        $this->ensureOwnedByCurrentUser($note);
        $note->load(['topic.parent', 'questions', 'linkedNotes']);

        return view('notes.show', [
            'note' => $note,
            'allNotes' => Note::where('user_id', Auth::id())->where('id', '!=', $note->id)->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function edit(Note $note): View
    {
        $this->ensureOwnedByCurrentUser($note);
        $note->load('linkedNotes');

        return view('notes.form', [
            'note' => $note,
            'topicGroups' => Topic::grouped(Auth::id()),
            'allNotes' => Note::where('user_id', Auth::id())->where('id', '!=', $note->id)->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($note);
        $note->update($this->validated($request));
        $this->syncLinks($note, $request);

        return redirect()->route('notes.show', $note)->with('success', 'Запись обновлена.');
    }

    public function destroy(Note $note): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($note);
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Запись удалена.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'visibility' => 'required|in:private,public',
            'content' => 'required|string',
            'recap' => 'nullable|string',
        ]);
    }

    private function syncLinks(Note $note, Request $request): void
    {
        $ids = $request->input('linked_note_ids', []);
        $note->linkedNotes()->sync(array_filter($ids));
    }
}
