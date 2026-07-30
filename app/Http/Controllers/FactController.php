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

    public function create(Request $request): View
    {
        $groupId = $request->filled('fact_group_id')
            ? $request->integer('fact_group_id')
            : null;

        if ($groupId && ! FactGroup::whereKey($groupId)->exists()) {
            abort(404);
        }

        return view('facts.form', [
            'fact' => new Fact(['fact_group_id' => $groupId]),
            'groups' => FactGroup::orderBy('name')->get(),
            'preselectedGroupId' => $groupId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $fact = Fact::create($this->validated($request));

        return redirect()
            ->to($this->indexUrl($fact))
            ->with('success', 'Факт сохранён.');
    }

    public function edit(Fact $fact): View
    {
        return view('facts.form', [
            'fact' => $fact,
            'groups' => FactGroup::orderBy('name')->get(),
            'preselectedGroupId' => null,
        ]);
    }

    public function update(Request $request, Fact $fact): RedirectResponse
    {
        $fact->update($this->validated($request));

        return redirect()
            ->to($this->indexUrl($fact))
            ->with('success', 'Факт обновлён.');
    }

    public function destroy(Fact $fact): RedirectResponse
    {
        $hash = $fact->fact_group_id
            ? 'fact-group-'.$fact->fact_group_id
            : 'fact-ungrouped';

        $fact->delete();

        return redirect()
            ->to(route('facts.index').'#'.$hash)
            ->with('success', 'Факт удалён.');
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

    private function indexUrl(Fact $fact): string
    {
        $hash = $fact->fact_group_id
            ? 'fact-group-'.$fact->fact_group_id
            : 'fact-ungrouped';

        return route('facts.index').'#'.$hash;
    }
}
