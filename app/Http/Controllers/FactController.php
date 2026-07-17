<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FactController extends Controller
{
    public function index(): View
    {
        return view('facts.index', [
            'facts' => Fact::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('facts.form', ['fact' => new Fact]);
    }

    public function store(Request $request): RedirectResponse
    {
        Fact::create($this->validated($request));

        return redirect()->route('facts.index')->with('success', 'Факт сохранён.');
    }

    public function edit(Fact $fact): View
    {
        return view('facts.form', compact('fact'));
    }

    public function update(Request $request, Fact $fact): RedirectResponse
    {
        $fact->update($this->validated($request));

        return redirect()->route('facts.index')->with('success', 'Факт обновлён.');
    }

    public function destroy(Fact $fact): RedirectResponse
    {
        $fact->delete();

        return redirect()->route('facts.index')->with('success', 'Факт удалён.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
            'source' => 'nullable|string|max:255',
        ]);
    }
}
