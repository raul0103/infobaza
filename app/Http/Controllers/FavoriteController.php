<?php

namespace App\Http\Controllers;

use App\Models\BookThought;
use App\Models\Phrase;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        return view('favorites.index', [
            'thoughts' => BookThought::with(['book', 'movie'])
                ->where('is_favorite', true)
                ->latest('updated_at')
                ->get(),
            'quotes' => Quote::with(['book', 'movie'])
                ->where('is_favorite', true)
                ->latest('updated_at')
                ->get(),
            'phrases' => Phrase::with(['book', 'movie'])
                ->where('is_favorite', true)
                ->latest('updated_at')
                ->get(),
        ]);
    }

    public function toggleThought(BookThought $thought): RedirectResponse
    {
        $thought->update(['is_favorite' => ! $thought->is_favorite]);

        return back()->with(
            'success',
            $thought->is_favorite ? 'Мысль добавлена в избранное.' : 'Мысль удалена из избранного.'
        );
    }

    public function toggleQuote(Quote $quote): RedirectResponse
    {
        $quote->update(['is_favorite' => ! $quote->is_favorite]);

        return back()->with(
            'success',
            $quote->is_favorite ? 'Цитата добавлена в избранное.' : 'Цитата удалена из избранного.'
        );
    }

    public function togglePhrase(Phrase $phrase): RedirectResponse
    {
        $phrase->update(['is_favorite' => ! $phrase->is_favorite]);

        return back()->with(
            'success',
            $phrase->is_favorite ? 'Оборот добавлен в избранное.' : 'Оборот удалён из избранного.'
        );
    }
}
