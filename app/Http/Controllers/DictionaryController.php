<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Support\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DictionaryController extends Controller
{
    public function index(): View
    {
        return view('dictionaries.index', [
            'dictionaries' => Dictionary::where('user_id', Auth::id())->withCount('entries')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('dictionaries.form', ['dictionary' => new Dictionary]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dictionary = Dictionary::create($this->validated($request) + ['user_id' => CurrentUser::id()]);

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Словарь создан.');
    }

    public function show(Dictionary $dictionary): View
    {
        $this->ensureOwnedByCurrentUser($dictionary);
        $dictionary->load(['entries' => fn ($q) => $q->orderByRaw('LOWER(term)')]);

        return view('dictionaries.show', compact('dictionary'));
    }

    public function edit(Dictionary $dictionary): View
    {
        $this->ensureOwnedByCurrentUser($dictionary);
        return view('dictionaries.form', compact('dictionary'));
    }

    public function update(Request $request, Dictionary $dictionary): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($dictionary);
        $dictionary->update($this->validated($request));

        return redirect()->route('dictionaries.show', $dictionary)->with('success', 'Словарь обновлён.');
    }

    public function destroy(Dictionary $dictionary): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($dictionary);
        $dictionary->delete();

        return redirect()->route('dictionaries.index')->with('success', 'Словарь удалён.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:50',
            'visibility' => 'required|in:private,public',
        ]);
    }
}
