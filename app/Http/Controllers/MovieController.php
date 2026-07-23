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
        $counts = Movie::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('movies.index', [
            'sections' => collect(Movie::statusLabels())->map(fn ($label, $status) => [
                'status' => $status,
                'label' => $label,
                'count' => (int) $counts->get($status, 0),
            ])->values(),
        ]);
    }

    public function status(string $status): View
    {
        abort_unless(array_key_exists($status, Movie::statusLabels()), 404);

        return view('movies.status', [
            'status' => $status,
            'label' => Movie::statusLabels()[$status],
            'movies' => Movie::withCount('quotes')
                ->where('status', $status)
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $status = $request->query('status', 'queued');
        if (! array_key_exists($status, Movie::statusLabels())) {
            $status = 'queued';
        }

        return view('movies.form', ['movie' => new Movie(['status' => $status])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $movie = Movie::create($this->validated($request));

        return redirect()->route('movies.status', $movie->status)->with('success', 'Фильм добавлен.');
    }

    public function show(Movie $movie): View
    {
        $movie->load([
            'quotes' => fn ($q) => $q->latest(),
            'tips' => fn ($q) => $q->latest(),
        ]);

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
        $status = $movie->status;
        $movie->delete();

        return redirect()->route('movies.status', $status)->with('success', 'Фильм удалён.');
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
