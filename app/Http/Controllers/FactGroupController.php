<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use App\Models\FactGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FactGroupController extends Controller
{
    public function create(): View
    {
        return view('facts.groups.form', [
            'group' => new FactGroup,
            'facts' => Fact::orderByRaw('COALESCE(title, text)')->get(),
            'selectedFactIds' => old('fact_ids', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            $group = FactGroup::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            $this->syncFacts($group, $data['fact_ids'] ?? []);
        });

        return redirect()->route('facts.index')->with('success', 'Группа фактов создана.');
    }

    public function edit(FactGroup $factGroup): View
    {
        $factGroup->load('facts');

        return view('facts.groups.form', [
            'group' => $factGroup,
            'facts' => Fact::orderByRaw('COALESCE(title, text)')->get(),
            'selectedFactIds' => old('fact_ids', $factGroup->facts->pluck('id')->all()),
        ]);
    }

    public function update(Request $request, FactGroup $factGroup): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($factGroup, $data) {
            $factGroup->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            $this->syncFacts($factGroup, $data['fact_ids'] ?? []);
        });

        return redirect()->route('facts.index')->with('success', 'Группа фактов обновлена.');
    }

    public function destroy(FactGroup $factGroup): RedirectResponse
    {
        $factGroup->delete();

        return redirect()
            ->route('facts.index')
            ->with('success', 'Группа удалена. Факты остались без группы.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fact_ids' => 'nullable|array',
            'fact_ids.*' => 'integer|distinct|exists:facts,id',
        ]);
    }

    private function syncFacts(FactGroup $group, array $factIds): void
    {
        $factIds = collect($factIds)->map(fn ($id) => (int) $id)->unique()->values();

        Fact::where('fact_group_id', $group->id)
            ->whereNotIn('id', $factIds)
            ->update(['fact_group_id' => null]);

        if ($factIds->isNotEmpty()) {
            Fact::whereIn('id', $factIds)->update(['fact_group_id' => $group->id]);
        }
    }
}
