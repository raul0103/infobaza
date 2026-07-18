<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use App\Models\DictionaryEntryGroup;
use App\Models\DictionaryGroupAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DictionaryEntryGroupController extends Controller
{
    public function create(Dictionary $dictionary): View
    {
        $dictionary->load(['entries' => fn ($q) => $q->orderByRaw('LOWER(term)')]);

        return view('dictionaries.groups.form', [
            'dictionary' => $dictionary,
            'group' => new DictionaryEntryGroup,
            'selectedEntryIds' => old('entry_ids', []),
        ]);
    }

    public function store(Request $request, Dictionary $dictionary): RedirectResponse
    {
        $data = $this->validated($request, $dictionary);

        $group = DB::transaction(function () use ($dictionary, $data, $request) {
            $group = $dictionary->entryGroups()->create([
                'title' => $data['title'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

            $this->syncEntries($dictionary, $group, $data['entry_ids'] ?? []);
            $this->storeAttachments($request, $group);

            return $group;
        });

        return redirect()
            ->route('dictionaries.show', $dictionary)
            ->with('success', 'Объединение создано.')
            ->with('highlight_group', $group->id);
    }

    public function edit(Dictionary $dictionary, DictionaryEntryGroup $group): View
    {
        $this->ensureGroupBelongsToDictionary($dictionary, $group);

        $dictionary->load(['entries' => fn ($q) => $q->orderByRaw('LOWER(term)')]);
        $group->load(['entries', 'attachments']);

        return view('dictionaries.groups.form', [
            'dictionary' => $dictionary,
            'group' => $group,
            'selectedEntryIds' => old('entry_ids', $group->entries->pluck('id')->all()),
        ]);
    }

    public function update(Request $request, Dictionary $dictionary, DictionaryEntryGroup $group): RedirectResponse
    {
        $this->ensureGroupBelongsToDictionary($dictionary, $group);
        $data = $this->validated($request, $dictionary);

        DB::transaction(function () use ($group, $data, $request) {
            $group->update([
                'title' => $data['title'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

            $this->syncEntries($group->dictionary, $group, $data['entry_ids'] ?? []);
            $this->storeAttachments($request, $group);
        });

        return redirect()
            ->route('dictionaries.show', $dictionary)
            ->with('success', 'Объединение обновлено.')
            ->with('highlight_group', $group->id);
    }

    public function destroy(Dictionary $dictionary, DictionaryEntryGroup $group): RedirectResponse
    {
        $this->ensureGroupBelongsToDictionary($dictionary, $group);

        $group->delete();

        return redirect()
            ->route('dictionaries.show', $dictionary)
            ->with('success', 'Объединение удалено. Слова остались в словаре.');
    }

    public function destroyAttachment(
        Dictionary $dictionary,
        DictionaryEntryGroup $group,
        DictionaryGroupAttachment $attachment
    ): RedirectResponse {
        $this->ensureGroupBelongsToDictionary($dictionary, $group);
        abort_unless($attachment->dictionary_entry_group_id === $group->id, 404);

        $attachment->delete();

        return redirect()
            ->route('dictionaries.groups.edit', [$dictionary, $group])
            ->with('success', 'Файл удалён.');
    }

    private function validated(Request $request, Dictionary $dictionary): array
    {
        return $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'entry_ids' => 'nullable|array',
            'entry_ids.*' => [
                'integer',
                'exists:dictionary_entries,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($dictionary) {
                    $belongs = DictionaryEntry::whereKey($value)
                        ->where('dictionary_id', $dictionary->id)
                        ->exists();

                    if (! $belongs) {
                        $fail('Слово не принадлежит этому словарю.');
                    }
                },
            ],
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,txt,doc,docx,xls,xlsx,zip',
        ]);
    }

    private function syncEntries(Dictionary $dictionary, DictionaryEntryGroup $group, array $entryIds): void
    {
        $entryIds = collect($entryIds)->map(fn ($id) => (int) $id)->unique()->values();

        DictionaryEntry::where('dictionary_id', $dictionary->id)
            ->where('group_id', $group->id)
            ->whereNotIn('id', $entryIds)
            ->update(['group_id' => null]);

        if ($entryIds->isEmpty()) {
            return;
        }

        DictionaryEntry::where('dictionary_id', $dictionary->id)
            ->whereIn('id', $entryIds)
            ->update(['group_id' => $group->id]);
    }

    private function storeAttachments(Request $request, DictionaryEntryGroup $group): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        $sortOrder = (int) $group->attachments()->max('sort_order');

        foreach ($request->file('attachments') as $file) {
            if (! $file) {
                continue;
            }

            $sortOrder++;
            $path = $file->store('dictionary-groups/'.$group->id, 'public');

            $group->attachments()->create([
                'disk' => 'public',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize() ?: 0,
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function ensureGroupBelongsToDictionary(Dictionary $dictionary, DictionaryEntryGroup $group): void
    {
        abort_unless($group->dictionary_id === $dictionary->id, 404);
    }
}
