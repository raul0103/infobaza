<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Services\ActivityTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(): View
    {
        return view('journal.index', [
            'entries' => JournalEntry::orderByDesc('entry_date')->paginate(15),
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
        $entry = JournalEntry::create($this->validated($request));
        ActivityTracker::log('journal');

        return redirect()->route('journal.show', $entry)->with('success', 'Запись дня сохранена.');
    }

    public function show(JournalEntry $entry): View
    {
        return view('journal.show', compact('entry'));
    }

    public function edit(JournalEntry $entry): View
    {
        return view('journal.form', compact('entry'));
    }

    public function update(Request $request, JournalEntry $entry): RedirectResponse
    {
        $entry->update($this->validated($request));

        return redirect()->route('journal.show', $entry)->with('success', 'Запись обновлена.');
    }

    public function destroy(JournalEntry $entry): RedirectResponse
    {
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
