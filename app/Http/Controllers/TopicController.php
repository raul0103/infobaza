<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function index(): View
    {
        return view('topics.index', [
            'groups' => Topic::grouped(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('topics.form', [
            'topic' => new Topic(['parent_id' => $request->parent_id]),
            'parents' => Topic::roots()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Topic::create($this->validated($request));

        return redirect()->route('topics.index')->with('success', 'Тема создана.');
    }

    public function show(Topic $topic): View
    {
        $topic->load([
            'parent',
            'children' => fn ($q) => $q->orderBy('name'),
            'notes' => fn ($q) => $q->latest(),
        ]);

        return view('topics.show', compact('topic'));
    }

    public function edit(Topic $topic): View
    {
        return view('topics.form', [
            'topic' => $topic,
            'parents' => Topic::roots()->where('id', '!=', $topic->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Topic $topic): RedirectResponse
    {
        $topic->update($this->validated($request));

        return redirect()->route('topics.show', $topic)->with('success', 'Тема обновлена.');
    }

    public function destroy(Topic $topic): RedirectResponse
    {
        $topic->delete();

        return redirect()->route('topics.index')->with('success', 'Тема удалена.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('topics', 'slug')->ignore($request->route('topic')?->id),
            ],
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable',
                'exists:topics,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if (! $value) {
                        return;
                    }
                    $parent = Topic::find($value);
                    if ($parent?->parent_id) {
                        $fail('Родителем может быть только основная тема (без своего родителя).');
                    }
                    $topic = $request->route('topic');
                    if ($topic && (int) $value === $topic->id) {
                        $fail('Тема не может быть родителем самой себе.');
                    }
                },
            ],
        ]);

        if (! empty($data['parent_id'])) {
            $data['color'] = null;
        }

        return $data;
    }
}
