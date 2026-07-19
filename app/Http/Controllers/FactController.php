<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use App\Models\FactGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FactController extends Controller
{
    public function index(): View
    {
        return view('facts.index', [
            'groups' => FactGroup::with(['facts' => fn ($query) => $query->latest()])
                ->orderBy('name')
                ->get(),
            'ungroupedFacts' => Fact::whereNull('fact_group_id')->latest()->get(),
            'totalFacts' => Fact::count(),
        ]);
    }

    public function create(): View
    {
        return view('facts.form', [
            'fact' => new Fact,
            'groups' => FactGroup::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Fact::create($this->validated($request));

        return redirect()->route('facts.index')->with('success', 'Факт сохранён.');
    }

    public function edit(Fact $fact): View
    {
        return view('facts.form', [
            'fact' => $fact,
            'groups' => FactGroup::orderBy('name')->get(),
        ]);
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
            'fact_group_id' => 'nullable|integer|exists:fact_groups,id',
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
            'source' => 'nullable|string|max:255',
        ]);
    }
}
