<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::withCount([
            'steps',
            'steps as steps_done_count' => fn ($q) => $q->where('is_done', true),
        ])
            ->latest()
            ->get()
            ->groupBy('status');

        return view('plans.index', [
            'sections' => collect(Plan::statusLabels())->map(fn ($label, $status) => [
                'status' => $status,
                'label' => $label,
                'plans' => $plans->get($status, collect()),
            ])->values(),
            'hasPlans' => $plans->flatten(1)->isNotEmpty(),
        ]);
    }

    public function status(string $status): View
    {
        abort_unless(array_key_exists($status, Plan::statusLabels()), 404);

        return view('plans.status', [
            'status' => $status,
            'label' => Plan::statusLabels()[$status],
            'plans' => Plan::withCount([
                'steps',
                'steps as steps_done_count' => fn ($q) => $q->where('is_done', true),
            ])
                ->where('status', $status)
                ->latest()
                ->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $status = $request->query('status', 'queued');
        if (! array_key_exists($status, Plan::statusLabels())) {
            $status = 'queued';
        }

        return view('plans.form', ['plan' => new Plan(['status' => $status])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $plan = Plan::create($this->validated($request));

        return redirect()->route('plans.show', $plan)->with('success', 'План создан.');
    }

    public function show(Plan $plan): View
    {
        $plan->load('steps');

        return view('plans.show', compact('plan'));
    }

    public function edit(Plan $plan): View
    {
        return view('plans.form', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $plan->update($this->validated($request));

        return redirect()->route('plans.show', $plan)->with('success', 'План обновлён.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $status = $plan->status;
        $plan->delete();

        if ($status === 'queued') {
            return redirect()->route('plans.index')->with('success', 'План удалён.');
        }

        return redirect()->route('plans.status', $status)->with('success', 'План удалён.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:queued,active,done',
        ]);
    }
}
