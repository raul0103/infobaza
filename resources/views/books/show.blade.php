@extends('layouts.app')
@section('title', $book->title)
@section('content')
<x-page-header :title="$book->title" :subtitle="trim(($book->author ?? '').($book->year ? ', '.$book->year : ''))">
    <x-slot:actions>
        <span class="badge-gray">{{ \App\Models\Book::statusLabels()[$book->status] ?? '' }}</span>
        <a href="{{ route('quotes.create', ['book_id' => $book->id]) }}" class="btn btn-primary">+ Цитата</a>
        <a href="{{ route('books.thoughts.create', $book) }}" class="btn btn-secondary">+ Мысль</a>
        @include('partials.item-actions', [
            'edit' => route('books.edit', $book),
            'destroy' => route('books.destroy', $book),
        ])
    </x-slot:actions>
</x-page-header>
@php
    $readingPercent = $book->readingPercent() ?? 0;
    $currentPage = (int) ($book->current_page ?? 0);
    $totalPages = (int) ($book->total_pages ?? 0);
    $quotesCountLabel = $q !== ''
        ? $book->quotes->count().' из '.$quotesTotal
        : $book->quotes->count();
@endphp
@if($totalPages > 0)
<div class="card mb-6 progress-control !p-3 sm:!p-4">
    <form method="POST" action="{{ route('books.progress', $book) }}" id="book-progress-form" class="space-y-1.5">
        @csrf
        @method('PATCH')
        <div class="flex justify-between text-xs sm:text-sm">
            <span class="text-gray-600">Прогресс чтения</span>
            <span class="font-medium tabular-nums">
                <span id="book-progress-label">{{ $currentPage }}</span> / {{ $totalPages }}
                <span class="text-gray-400">(<span id="book-progress-percent">{{ $readingPercent }}</span>%)</span>
            </span>
        </div>
        <input
            type="range"
            name="current_page"
            id="book-progress-range"
            class="reading-range w-full"
            min="0"
            max="{{ $totalPages }}"
            step="1"
            value="{{ min($currentPage, $totalPages) }}"
            style="--reading-progress: {{ $readingPercent }}%"
            aria-label="Текущая страница"
        >
        @error('current_page')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
    </form>
</div>
@elseif($book->status === 'reading')
<div class="card mb-6 text-sm text-gray-500">
    Укажите общее число страниц в
    <a href="{{ route('books.edit', $book) }}" class="link">редактировании книги</a>,
    чтобы регулировать прогресс ползунком.
</div>
@endif
@if($book->description)<div class="card mb-6 text-gray-600">{{ $book->description }}</div>@endif

<x-collapsible title="Цитаты" :count="$quotesCountLabel" :open="true">
    <x-slot:actions>
        <a href="{{ route('quotes.create', ['book_id' => $book->id]) }}" class="link">+ Добавить</a>
    </x-slot:actions>

    <form method="GET" action="{{ route('books.show', $book) }}" class="mb-3" onclick="event.stopPropagation()">
        <div class="flex gap-2">
            <input
                type="search"
                name="q"
                value="{{ $q }}"
                class="input flex-1"
                placeholder="Поиск по цитате, персонажу, странице…"
            >
            <button type="submit" class="btn btn-secondary shrink-0">Найти</button>
            @if($q !== '')
                <a href="{{ route('books.show', $book) }}" class="btn btn-secondary shrink-0">Сброс</a>
            @endif
        </div>
    </form>

    <div class="space-y-2">
        @forelse($book->quotes as $quote)
            @include('quotes.card', ['quote' => $quote])
        @empty
            <div class="card text-center py-10 text-gray-500">
                @if($q !== '')
                    Ничего не найдено по «{{ $q }}»
                @else
                    Выписывайте цитаты по мере чтения
                @endif
            </div>
        @endforelse
    </div>
</x-collapsible>

<x-collapsible title="Мои мысли" :count="$book->thoughts->count()" :open="$book->thoughts->isNotEmpty() && $book->quotes->isEmpty()">
    <x-slot:actions>
        <a href="{{ route('books.thoughts.create', $book) }}" class="link">+ Добавить мысль</a>
    </x-slot:actions>

    @forelse($book->thoughts as $thought)
        @include('books.thoughts.card', ['thought' => $thought])
    @empty
        <div class="card text-center py-8 text-gray-500">
            Записывайте свои выводы, вопросы и идеи по мере чтения.
        </div>
    @endforelse
</x-collapsible>

@if($totalPages > 0)
@push('head')
<style>
    .reading-range {
        appearance: none;
        -webkit-appearance: none;
        height: 18px;
        margin: 0;
        background: transparent;
        cursor: default;
    }

    .reading-range::-webkit-slider-runnable-track {
        height: 4px;
        border-radius: 9999px;
        background: linear-gradient(
            to right,
            #86efac 0%,
            #86efac var(--reading-progress),
            #e5e7eb var(--reading-progress),
            #e5e7eb 100%
        );
        transition: box-shadow 160ms ease, background 160ms ease;
    }

    .reading-range::-moz-range-track {
        height: 4px;
        border-radius: 9999px;
        background: #e5e7eb;
    }

    .reading-range::-moz-range-progress {
        height: 4px;
        border-radius: 9999px;
        background: #86efac;
    }

    .reading-range::-webkit-slider-thumb {
        appearance: none;
        -webkit-appearance: none;
        width: 14px;
        height: 14px;
        margin-top: -5px;
        border: 2px solid #34d399;
        border-radius: 9999px;
        background: #fff;
        box-shadow: 0 1px 4px rgb(15 23 42 / 0.16);
        opacity: 0;
        transform: scale(0.75);
        transition: opacity 140ms ease, transform 140ms ease;
    }

    .reading-range::-moz-range-thumb {
        width: 10px;
        height: 10px;
        border: 2px solid #34d399;
        border-radius: 9999px;
        background: #fff;
        box-shadow: 0 1px 4px rgb(15 23 42 / 0.16);
        opacity: 0;
        transform: scale(0.75);
        transition: opacity 140ms ease, transform 140ms ease;
    }

    .progress-control:hover .reading-range,
    .reading-range:focus-visible {
        cursor: pointer;
        outline: none;
    }

    .progress-control:hover .reading-range::-webkit-slider-thumb,
    .reading-range:focus-visible::-webkit-slider-thumb,
    .reading-range:active::-webkit-slider-thumb {
        opacity: 1;
        transform: scale(1);
    }

    .progress-control:hover .reading-range::-moz-range-thumb,
    .reading-range:focus-visible::-moz-range-thumb,
    .reading-range:active::-moz-range-thumb {
        opacity: 1;
        transform: scale(1);
    }

    .progress-control:hover .reading-range::-webkit-slider-runnable-track,
    .reading-range:focus-visible::-webkit-slider-runnable-track {
        box-shadow: 0 0 0 3px rgb(16 185 129 / 0.08);
    }

    @media (hover: hover) and (pointer: fine) {
        .reading-range {
            pointer-events: none;
        }

        .progress-control:hover .reading-range,
        .reading-range:focus-visible {
            pointer-events: auto;
        }
    }
</style>
@endpush

@push('scripts')
<script>
(() => {
    const form = document.getElementById('book-progress-form');
    const range = document.getElementById('book-progress-range');
    const label = document.getElementById('book-progress-label');
    const percent = document.getElementById('book-progress-percent');
    if (!form || !range || !label || !percent) return;

    const total = Number(range.max) || 0;
    const initialValue = range.value;

    const update = () => {
        const value = Number(range.value) || 0;
        const progress = total ? Math.min(100, Math.round((value / total) * 100)) : 0;
        label.textContent = String(value);
        percent.textContent = String(progress);
        range.style.setProperty('--reading-progress', `${progress}%`);
    };

    const save = () => {
        if (range.value === initialValue) return;
        form.submit();
    };

    range.addEventListener('input', update);
    range.addEventListener('change', save);
    update();
})();
</script>
@endpush
@endif
@endsection
