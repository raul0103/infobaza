<?php

namespace App\Http\Controllers;

use App\Models\Joke;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JokeController extends Controller
{
    public function index(): View
    {
        return view('jokes.index', [
            'jokes' => Joke::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('jokes.form', ['joke' => new Joke]);
    }

    public function store(Request $request): RedirectResponse
    {
        Joke::create($this->validated($request));

        return redirect()->route('jokes.index')->with('success', 'Анекдот сохранён.');
    }

    public function edit(Joke $joke): View
    {
        return view('jokes.form', compact('joke'));
    }

    public function update(Request $request, Joke $joke): RedirectResponse
    {
        $joke->update($this->validated($request));

        return redirect()->route('jokes.index')->with('success', 'Анекдот обновлён.');
    }

    public function destroy(Joke $joke): RedirectResponse
    {
        $joke->delete();

        return redirect()->route('jokes.index')->with('success', 'Анекдот удалён.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'text' => 'required|string',
            'source' => 'nullable|string|max:255',
        ]);
    }
}
