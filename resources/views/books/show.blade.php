@extends('layouts.app')
@section('title', $book->title)
@section('content')
<x-page-header :title="$book->title" :subtitle="trim(($book->author ?? '').($book->year ? ', '.$book->year : ''))">
    <x-slot:actions>
        <span class="badge-gray">{{ \App\Models\Book::statusLabels()[$book->status] ?? '' }}</span>
        <a href="{{ route('books.thoughts.create', $book) }}" class="btn btn-secondary">+ Мысль</a>
        <a href="{{ route('quotes.create', ['book_id' => $book->id]) }}" class="btn btn-primary">+ Цитата</a>
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
@endphp
@if($totalPages > 0)
<div class="card mb-6 progress-control">
    <form method="POST" action="{{ route('books.progress', $book) }}" id="book-progress-form" class="space-y-3">
        @csrf
        @method('PATCH')
        <div class="flex justify-between text-sm">
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
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="progress-hint text-xs text-gray-400 transition-colors">Наведите на шкалу, чтобы изменить прогресс.</p>
            <button type="submit" id="book-progress-submit" class="btn btn-secondary" disabled>Сохранить прогресс</button>
        </div>
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

<div class="flex flex-wrap items-center justify-between gap-3 mt-8 mb-4">
    <h2 class="section-title">Мои мысли <span class="text-gray-400 font-normal">({{ $book->thoughts->count() }})</span></h2>
    <a href="{{ route('books.thoughts.create', $book) }}" class="link">+ Добавить мысль</a>
</div>
<div class="space-y-4">
    @forelse($book->thoughts as $thought)
        <div class="card border-l-4 border-l-amber-400 p-3 sm:p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="text-sm text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $thought->content }}</div>
                    @if($thought->chapter || $thought->page)
                        <div class="flex flex-wrap gap-2 mt-3">
                            @if($thought->chapter)<span class="badge-gray">{{ $thought->chapter }}</span>@endif
                            @if($thought->page)<span class="badge-gray">Стр. {{ $thought->page }}</span>@endif
                        </div>
                    @endif
                </div>
                @include('partials.item-actions', [
                    'edit' => route('books.thoughts.edit', [$book, $thought]),
                    'destroy' => route('books.thoughts.destroy', [$book, $thought]),
                ])
            </div>
        </div>
    @empty
        <div class="card text-center py-8 text-gray-500">
            Записывайте свои выводы, вопросы и идеи по мере чтения.
        </div>
    @endforelse
</div>

<div class="flex flex-wrap items-center justify-between gap-3 mt-8 mb-4">
    <h2 class="section-title">Цитаты <span class="text-gray-400 font-normal">({{ $book->quotes->count() }})</span></h2>
    <a href="{{ route('quotes.create', ['book_id' => $book->id]) }}" class="link">+ Добавить цитату</a>
</div>
<div class="space-y-4">
    @forelse($book->quotes as $quote)
        <div class="card border-l-4 border-l-blue-500 p-3 sm:p-3">
            <blockquote class="text-sm text-gray-800 italic leading-relaxed">«{{ $quote->text }}»</blockquote>
            @if($quote->page)<p class="text-sm text-gray-500 mt-3">Стр. {{ $quote->page }}</p>@endif
            <div class="mt-3 pt-2 border-t border-gray-100">
                @include('partials.item-actions', [
                    'edit' => route('quotes.edit', $quote),
                    'destroy' => route('quotes.destroy', $quote),
                ])
            </div>
        </div>
    @empty
        <div class="card text-center py-10 text-gray-500">Выписывайте цитаты по мере чтения</div>
    @endforelse
</div>

@if($totalPages > 0)
@push('head')
<style>
    .reading-range {
        appearance: none;
        -webkit-appearance: none;
        height: 24px;
        margin: 0;
        background: transparent;
        cursor: default;
    }

    .reading-range::-webkit-slider-runnable-track {
        height: 5px;
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
        height: 5px;
        border-radius: 9999px;
        background: #e5e7eb;
    }

    .reading-range::-moz-range-progress {
        height: 5px;
        border-radius: 9999px;
        background: #86efac;
    }

    .reading-range::-webkit-slider-thumb {
        appearance: none;
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        margin-top: -5.5px;
        border: 3px solid #34d399;
        border-radius: 9999px;
        background: #fff;
        box-shadow: 0 1px 4px rgb(15 23 42 / 0.16);
        opacity: 0;
        transform: scale(0.75);
        transition: opacity 140ms ease, transform 140ms ease;
    }

    .reading-range::-moz-range-thumb {
        width: 12px;
        height: 12px;
        border: 3px solid #34d399;
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
        box-shadow: 0 0 0 4px rgb(16 185 129 / 0.08);
    }

    .progress-control:hover .progress-hint {
        color: #6b7280;
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
    const range = document.getElementById('book-progress-range');
    const label = document.getElementById('book-progress-label');
    const percent = document.getElementById('book-progress-percent');
    const submit = document.getElementById('book-progress-submit');
    if (!range || !label || !percent || !submit) return;

    const total = Number(range.max) || 0;
    const initialValue = range.value;
    const update = () => {
        const value = Number(range.value) || 0;
        const progress = total ? Math.min(100, Math.round((value / total) * 100)) : 0;
        label.textContent = String(value);
        percent.textContent = String(progress);
        range.style.setProperty('--reading-progress', `${progress}%`);
        submit.disabled = range.value === initialValue;

        if (submit.disabled) {
            submit.classList.remove('btn-success');
            submit.classList.add('btn-secondary');
        } else {
            submit.classList.remove('btn-secondary');
            submit.classList.add('btn-success');
        }
    };

    range.addEventListener('input', update);
    update();
})();
</script>
@endpush
@endif
@endsection
