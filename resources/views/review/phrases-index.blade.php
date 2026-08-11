@extends('layouts.app')
@section('title', 'Повторение — обороты речи')

@section('content')
<x-page-header title="Повторение оборотов" subtitle="Выберите источник или повторите все" :back="route('phrases.index')" back-label="К оборотам" />

@if($totalPhrases > 0)
    <a href="{{ route('review.phrases.all') }}" class="card-hover !p-4 flex items-center gap-3 mb-4 group">
        <div class="min-w-0 flex-1">
            <div class="font-medium text-gray-900 group-hover:text-emerald-700">Все обороты</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $totalPhrases }} оборотов</div>
        </div>
        <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    @if($books->isNotEmpty())
        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-2 px-0.5">Книги</p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            @foreach($books as $book)
                <a href="{{ route('review.phrases.book', $book) }}" class="card-hover !p-3 flex items-center gap-2 group">
                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-gray-900 group-hover:text-blue-600 truncate">{{ $book->title }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $book->phrases_count }} оборотов</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif

    @if($movies->isNotEmpty())
        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-2 px-0.5">Фильмы</p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($movies as $movie)
                <a href="{{ route('review.phrases.movie', $movie) }}" class="card-hover !p-3 flex items-center gap-2 group">
                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-gray-900 group-hover:text-blue-600 truncate">{{ $movie->title }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $movie->phrases_count }} оборотов</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif
@else
    <div class="card text-center py-12 text-gray-500">
        <p class="mb-3">Пока нет оборотов для повторения.</p>
        <a href="{{ route('phrases.index') }}" class="btn btn-primary">К оборотам</a>
    </div>
@endif
@endsection
