<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlanStepController extends Controller
{
    public function store(Request $request, Plan $plan): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $position = ((int) $plan->steps()->max('position')) + 1;

        $plan->steps()->create([
            'title' => $data['title'],
            'position' => $position,
        ]);

        return redirect()->route('plans.show', $plan)->with('success', 'Шаг добавлен.');
    }

    public function toggle(Plan $plan, PlanStep $planStep): RedirectResponse
    {
        abort_unless($planStep->plan_id === $plan->id, 404);

        $planStep->update(['is_done' => ! $planStep->is_done]);

        return redirect()->route('plans.show', $plan);
    }

    public function destroy(Plan $plan, PlanStep $planStep): RedirectResponse
    {
        abort_unless($planStep->plan_id === $plan->id, 404);

        $planStep->delete();

        return redirect()->route('plans.show', $plan)->with('success', 'Шаг удалён.');
    }
}
