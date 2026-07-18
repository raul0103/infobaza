@extends('layouts.app')
@section('title', $book->title)
@section('content')
<x-page-header :title="$book->title" :subtitle="trim(($book->author ?? '').($book->year ? ', '.$book->year : ''))">
    <x-slot:actions>
        <span class="badge-gray">{{ \App\Models\Book::statusLabels()[$book->status] ?? '' }}</span>
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
<div class="card mb-6">
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
            class="w-full accent-emerald-500 cursor-pointer"
            min="0"
            max="{{ $totalPages }}"
            step="1"
            value="{{ min($currentPage, $totalPages) }}"
        >
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-gray-500">Перетащите ползунок и нажмите «Сохранить прогресс».</p>
            <button type="submit" class="btn btn-success">Сохранить прогресс</button>
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
<h2 class="section-title mb-4">Цитаты <span class="text-gray-400 font-normal">({{ $book->quotes->count() }})</span></h2>
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
@push('scripts')
<script>
(() => {
    const range = document.getElementById('book-progress-range');
    const label = document.getElementById('book-progress-label');
    const percent = document.getElementById('book-progress-percent');
    if (!range || !label || !percent) return;

    const total = Number(range.max) || 0;
    const update = () => {
        const value = Number(range.value) || 0;
        label.textContent = String(value);
        percent.textContent = String(total ? Math.min(100, Math.round((value / total) * 100)) : 0);
    };

    range.addEventListener('input', update);
    update();
})();
</script>
@endpush
@endif
@endsection
