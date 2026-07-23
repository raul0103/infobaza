<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use App\Models\MemoEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemoController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $searchResults = collect();
        if ($q !== '') {
            $searchResults = MemoEntry::query()
                ->with('memo')
                ->search($q)
                ->orderByRaw('LOWER(title)')
                ->limit(100)
                ->get();
        }

        return view('memos.index', [
            'memos' => Memo::withCount('entries')->orderBy('name')->get(),
            'q' => $q,
            'searchResults' => $searchResults,
        ]);
    }

    public function create(): View
    {
        return view('memos.form', ['memo' => new Memo]);
    }

    public function store(Request $request): RedirectResponse
    {
        $memo = Memo::create($this->validated($request));

        return redirect()->route('memos.show', $memo)->with('success', 'Категория создана.');
    }

    public function show(Request $request, Memo $memo): View
    {
        $q = trim((string) $request->query('q', ''));

        $memo->load([
            'entries' => fn ($query) => $query
                ->search($q)
                ->orderByRaw('LOWER(title)'),
        ]);

        return view('memos.show', [
            'memo' => $memo,
            'q' => $q,
            'totalEntries' => $memo->entries()->count(),
        ]);
    }

    public function edit(Memo $memo): View
    {
        return view('memos.form', compact('memo'));
    }

    public function update(Request $request, Memo $memo): RedirectResponse
    {
        $memo->update($this->validated($request));

        return redirect()->route('memos.show', $memo)->with('success', 'Категория обновлена.');
    }

    public function destroy(Memo $memo): RedirectResponse
    {
        $memo->delete();

        return redirect()->route('memos.index')->with('success', 'Категория удалена.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    }
}
