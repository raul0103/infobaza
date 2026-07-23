<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use App\Models\MemoEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemoEntryController extends Controller
{
    public function create(Memo $memo): View
    {
        return view('memos.entries.form', [
            'memo' => $memo,
            'entry' => new MemoEntry,
        ]);
    }

    public function store(Request $request, Memo $memo): RedirectResponse
    {
        $entry = $memo->entries()->create($this->validated($request));

        return redirect()->route('memos.show', $memo)->with('success', 'Заметка добавлена.');
    }

    public function show(Memo $memo, MemoEntry $entry): RedirectResponse
    {
        abort_unless($entry->memo_id === $memo->id, 404);

        return redirect()->route('memos.show', $memo);
    }

    public function edit(Memo $memo, MemoEntry $entry): View
    {
        abort_unless($entry->memo_id === $memo->id, 404);

        return view('memos.entries.form', compact('memo', 'entry'));
    }

    public function update(Request $request, Memo $memo, MemoEntry $entry): RedirectResponse
    {
        abort_unless($entry->memo_id === $memo->id, 404);

        $entry->update($this->validated($request));

        return redirect()->route('memos.show', $memo)->with('success', 'Заметка обновлена.');
    }

    public function destroy(Memo $memo, MemoEntry $entry): RedirectResponse
    {
        abort_unless($entry->memo_id === $memo->id, 404);

        $entry->delete();

        return redirect()->route('memos.show', $memo)->with('success', 'Заметка удалена.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);
    }
}
