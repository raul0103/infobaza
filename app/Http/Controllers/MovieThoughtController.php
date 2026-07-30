<?php

namespace App\Http\Controllers;

use App\Models\BookThought;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieThoughtController extends Controller
{
    public function create(Movie $movie): View
    {
        return view('movies.thoughts.form', [
            'movie' => $movie,
            'thought' => new BookThought,
        ]);
    }

    public function store(Request $request, Movie $movie): RedirectResponse
    {
        $movie->thoughts()->create($this->validated($request));

        return redirect()
            ->route('movies.show', $movie)
            ->with('success', 'Мысль о фильме сохранена.');
    }

    public function edit(Movie $movie, BookThought $thought): View
    {
        $this->ensureThoughtBelongsToMovie($movie, $thought);

        return view('movies.thoughts.form', compact('movie', 'thought'));
    }

    public function update(Request $request, Movie $movie, BookThought $thought): RedirectResponse
    {
        $this->ensureThoughtBelongsToMovie($movie, $thought);
        $thought->update($this->validated($request));

        return redirect()
            ->route('movies.show', $movie)
            ->with('success', 'Мысль обновлена.');
    }

    public function destroy(Movie $movie, BookThought $thought): RedirectResponse
    {
        $this->ensureThoughtBelongsToMovie($movie, $thought);
        $thought->delete();

        return redirect()
            ->route('movies.show', $movie)
            ->with('success', 'Мысль удалена.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'content' => 'required|string',
            'chapter' => 'nullable|string|max:255',
            'page' => 'nullable|string|max:50',
        ]);
    }

    private function ensureThoughtBelongsToMovie(Movie $movie, BookThought $thought): void
    {
        abort_unless($thought->movie_id === $movie->id, 404);
    }
}
