<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DictionaryController extends Controller
{
    public function index(): View
    {
        return view('dictionaries.index', [
            'dictionaries' => Dictionary::withCount('entries')->orderBy('name')->get(),
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

    public function show(Dictionary $dictionary): View
    {
        $dictionary->load([
            'entries' => fn ($q) => $q->with('group')->orderByRaw('LOWER(term)'),
            'entryGroups' => fn ($q) => $q
                ->with([
                    'entries' => fn ($entries) => $entries->orderByRaw('LOWER(term)'),
                    'attachments',
                ])
                ->latest(),
        ]);

        return view('dictionaries.show', compact('dictionary'));
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
