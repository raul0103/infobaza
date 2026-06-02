<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(): View
    {
        $movies = Movie::withCount('quotes')->orderBy('title')->get()->groupBy('status');

        return view('movies.index', [
            'sections' => collect(Movie::statusLabels())->map(fn ($label, $status) => [
                'status' => $status,
                'label' => $label,
                'movies' => $movies->get($status, collect()),
            ])->values(),
        ]);
    }

    public function create(): View
    {
        return view('movies.form', ['movie' => new Movie(['status' => 'queued'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $movie = Movie::create($this->validated($request));

        return redirect()->route('movies.show', $movie)->with('success', 'Фильм добавлен.');
    }

    public function show(Movie $movie): View
    {
        $movie->load(['quotes' => fn ($q) => $q->latest()]);

        return view('movies.show', compact('movie'));
    }

    public function edit(Movie $movie): View
    {
        return view('movies.form', compact('movie'));
    }

    public function update(Request $request, Movie $movie): RedirectResponse
    {
        $movie->update($this->validated($request));

        return redirect()->route('movies.show', $movie)->with('success', 'Фильм обновлен.');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Фильм удалён.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1|max:2100',
            'description' => 'nullable|string',
            'status' => 'required|in:queued,watching,watched',
        ]);
    }
}
