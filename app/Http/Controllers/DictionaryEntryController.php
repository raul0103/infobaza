<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DictionaryEntryController extends Controller
{
    public function create(Dictionary $dictionary): View
    {
        return view('dictionaries.entries.form', [
            'dictionary' => $dictionary,
            'entry' => new DictionaryEntry,
        ]);
    }

    public function store(Request $request, Dictionary $dictionary): RedirectResponse
    {
        $dictionary->entries()->create($this->validated($request));

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Слово добавлено.');
    }

    public function edit(Dictionary $dictionary, DictionaryEntry $entry): View
    {
        return view('dictionaries.entries.form', compact('dictionary', 'entry'));
    }

    public function update(Request $request, Dictionary $dictionary, DictionaryEntry $entry): RedirectResponse
    {
        $entry->update($this->validated($request));

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Слово обновлено.');
    }

    public function destroy(Dictionary $dictionary, DictionaryEntry $entry): RedirectResponse
    {
        abort_unless($entry->dictionary_id === $dictionary->id, 404);

        $entry->delete();

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Слово удалено.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'term' => 'required|string|max:255',
            'definition' => 'required|string',
            'example' => 'nullable|string',
        ]);
    }
}
