<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DictionaryEntryController extends Controller
{
    public function create(Dictionary $dictionary): View
    {
        $this->loadGroupsForForm($dictionary);

        return view('dictionaries.entries.form', [
            'dictionary' => $dictionary,
            'entry' => new DictionaryEntry,
            'peerEntries' => $this->peerEntries($dictionary),
            'synonymIds' => old('synonym_ids', []),
            'antonymIds' => old('antonym_ids', []),
        ]);
    }

    public function store(Request $request, Dictionary $dictionary): RedirectResponse
    {
        $data = $this->validated($request, $dictionary);
        $relations = $this->validatedRelations($request, $dictionary);

        $entry = $dictionary->entries()->create($data);
        $entry->syncRelations(DictionaryEntry::RELATION_SYNONYM, $relations['synonym_ids']);
        $entry->syncRelations(DictionaryEntry::RELATION_ANTONYM, $relations['antonym_ids']);

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Слово добавлено.');
    }

    public function edit(Dictionary $dictionary, DictionaryEntry $entry): View
    {
        abort_unless($entry->dictionary_id === $dictionary->id, 404);
        $this->loadGroupsForForm($dictionary);
        $entry->load(['synonyms', 'antonyms']);

        return view('dictionaries.entries.form', [
            'dictionary' => $dictionary,
            'entry' => $entry,
            'peerEntries' => $this->peerEntries($dictionary, $entry),
            'synonymIds' => old('synonym_ids', $entry->synonyms->pluck('id')->all()),
            'antonymIds' => old('antonym_ids', $entry->antonyms->pluck('id')->all()),
        ]);
    }

    public function update(Request $request, Dictionary $dictionary, DictionaryEntry $entry): RedirectResponse
    {
        abort_unless($entry->dictionary_id === $dictionary->id, 404);

        $data = $this->validated($request, $dictionary);
        $relations = $this->validatedRelations($request, $dictionary, $entry);

        $entry->update($data);
        $entry->syncRelations(DictionaryEntry::RELATION_SYNONYM, $relations['synonym_ids']);
        $entry->syncRelations(DictionaryEntry::RELATION_ANTONYM, $relations['antonym_ids']);

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

    /**
     * @return array{synonym_ids: list<int>, antonym_ids: list<int>}
     */
    private function validatedRelations(Request $request, Dictionary $dictionary, ?DictionaryEntry $entry = null): array
    {
        $peerRule = Rule::exists('dictionary_entries', 'id')->where('dictionary_id', $dictionary->id);

        $data = $request->validate([
            'synonym_ids' => 'nullable|array',
            'synonym_ids.*' => ['integer', 'distinct', $peerRule],
            'antonym_ids' => 'nullable|array',
            'antonym_ids.*' => ['integer', 'distinct', $peerRule],
        ]);

        $synonymIds = collect($data['synonym_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        $antonymIds = collect($data['antonym_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();

        if ($entry) {
            $synonymIds = $synonymIds->reject(fn (int $id) => $id === (int) $entry->id)->values();
            $antonymIds = $antonymIds->reject(fn (int $id) => $id === (int) $entry->id)->values();
        }

        $overlap = $synonymIds->intersect($antonymIds);
        if ($overlap->isNotEmpty()) {
            throw ValidationException::withMessages([
                'antonym_ids' => 'Одно и то же слово нельзя указать и как синоним, и как антоним.',
            ]);
        }

        return [
            'synonym_ids' => $synonymIds->all(),
            'antonym_ids' => $antonymIds->all(),
        ];
    }

    private function peerEntries(Dictionary $dictionary, ?DictionaryEntry $except = null)
    {
        return $dictionary->entries()
            ->when($except, fn ($q) => $q->where('id', '!=', $except->id))
            ->orderByRaw('LOWER(term)')
            ->get(['id', 'term']);
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
