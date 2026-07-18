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
        $this->loadGroupsForForm($dictionary);

        return view('dictionaries.entries.form', [
            'dictionary' => $dictionary,
            'entry' => new DictionaryEntry,
        ]);
    }

    public function store(Request $request, Dictionary $dictionary): RedirectResponse
    {
        $dictionary->entries()->create($this->validated($request, $dictionary));

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Слово добавлено.');
    }

    public function edit(Dictionary $dictionary, DictionaryEntry $entry): View
    {
        abort_unless($entry->dictionary_id === $dictionary->id, 404);
        $this->loadGroupsForForm($dictionary);

        return view('dictionaries.entries.form', compact('dictionary', 'entry'));
    }

    public function update(Request $request, Dictionary $dictionary, DictionaryEntry $entry): RedirectResponse
    {
        abort_unless($entry->dictionary_id === $dictionary->id, 404);

        $entry->update($this->validated($request, $dictionary));

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Слово обновлено.');
    }

    public function destroy(Dictionary $dictionary, DictionaryEntry $entry): RedirectResponse
    {
        abort_unless($entry->dictionary_id === $dictionary->id, 404);

        $entry->delete();

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Слово удалено.');
    }

    private function validated(Request $request, Dictionary $dictionary): array
    {
        $data = $request->validate([
            'term' => 'required|string|max:255',
            'definition' => 'required|string',
            'example' => 'nullable|string',
            'group_id' => 'nullable|integer|exists:dictionary_entry_groups,id',
        ]);

        if (! empty($data['group_id'])) {
            $belongs = $dictionary->entryGroups()->whereKey($data['group_id'])->exists();
            abort_unless($belongs, 422);
        } else {
            $data['group_id'] = null;
        }

        return $data;
    }

    private function loadGroupsForForm(Dictionary $dictionary): void
    {
        $dictionary->load([
            'entryGroups' => fn ($q) => $q
                ->with(['entries' => fn ($entries) => $entries->orderByRaw('LOWER(term)')])
                ->orderBy('title')
                ->orderBy('id'),
        ]);
    }
}
