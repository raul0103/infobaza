<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DictionaryController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $searchResults = collect();
        if ($q !== '') {
            $searchResults = DictionaryEntry::query()
                ->with('dictionary')
                ->search($q)
                ->orderByRaw('LOWER(term)')
                ->limit(100)
                ->get();
        }

        return view('dictionaries.index', [
            'dictionaries' => Dictionary::withCount('entries')->orderBy('name')->get(),
            'q' => $q,
            'searchResults' => $searchResults,
        ]);
    }

    public function create(): View
    {
        return view('dictionaries.form', ['dictionary' => new Dictionary]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dictionary = Dictionary::create($this->validated($request));

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Словарь создан.');
    }

    public function show(Request $request, Dictionary $dictionary): View
    {
        $q = trim((string) $request->query('q', ''));

        $dictionary->load([
            'entries' => fn ($query) => $query
                ->with(['group', 'synonyms', 'antonyms'])
                ->search($q)
                ->orderByRaw('LOWER(term)'),
            'entryGroups' => fn ($query) => $query
                ->with([
                    'entries' => fn ($entries) => $entries
                        ->with(['synonyms', 'antonyms'])
                        ->orderByRaw('LOWER(term)'),
                    'attachments',
                ])
                ->latest(),
        ]);

        return view('dictionaries.show', [
            'dictionary' => $dictionary,
            'q' => $q,
            'totalEntries' => $dictionary->entries()->count(),
        ]);
    }

    public function edit(Dictionary $dictionary): View
    {
        return view('dictionaries.form', compact('dictionary'));
    }

    public function update(Request $request, Dictionary $dictionary): RedirectResponse
    {
        $dictionary->update($this->validated($request));

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Словарь обновлён.');
    }

    public function destroy(Dictionary $dictionary): RedirectResponse
    {
        $dictionary->delete();

        return redirect()->route('dictionaries.index')->with('success', 'Словарь удалён.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:50',
        ]);
    }
}
