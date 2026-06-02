<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Services\ActivityTracker;
use App\Support\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(): View
    {
        return view('journal.index', [
            'entries' => JournalEntry::where('user_id', Auth::id())->orderByDesc('entry_date')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('journal.form', [
            'entry' => new JournalEntry(['entry_date' => today()]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $entry = JournalEntry::create($this->validated($request) + ['user_id' => CurrentUser::id(), 'visibility' => 'private']);
        ActivityTracker::log('journal');

        return redirect()->route('journal.show', $entry)->with('success', 'Запись дня сохранена.');
    }

    public function show(JournalEntry $entry): View
    {
        $this->ensureOwnedByCurrentUser($entry);
        return view('journal.show', compact('entry'));
    }

    public function edit(JournalEntry $entry): View
    {
        $this->ensureOwnedByCurrentUser($entry);
        return view('journal.form', compact('entry'));
    }

    public function update(Request $request, JournalEntry $entry): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($entry);
        $entry->update($this->validated($request) + ['visibility' => 'private']);

        return redirect()->route('journal.show', $entry)->with('success', 'Запись обновлена.');
    }

    public function destroy(JournalEntry $entry): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($entry);
        $entry->delete();

        return redirect()->route('journal.index')->with('success', 'Запись удалена.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'entry_date' => 'required|date',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'mood' => 'nullable|string|max:50',
        ]);
    }
}
