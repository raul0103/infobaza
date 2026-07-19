@extends('layouts.app')
@section('title', 'Книги')
@section('content')
<x-page-header title="Книги" subtitle="Библиотека и цитаты из книг">
    <x-slot:actions><a href="{{ route('books.create') }}" class="btn btn-primary">+ Книга</a></x-slot:actions>
</x-page-header>

@php $hasBooks = $sections->sum(fn ($s) => $s['books']->count()) > 0; @endphp

@foreach($sections as $section)
    @if($section['books']->isNotEmpty())
        @if($section['status'] === 'reading')
            <div class="card mt-6 first:mt-0">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="section-title">Сейчас читаю</h2>
                    <span class="badge-gray">{{ $section['books']->count() }}</span>
                </div>
                @include('partials.reading-books', [
                    'books' => $section['books'],
                    'showActions' => true,
                ])
            </div>
        @else
            <x-collapsible
                :title="$section['label']"
                :count="$section['books']->count()"
            >
                @if($section['status'] === 'queued')
                    <p class="text-xs text-gray-500 -mt-1">Перетаскивайте книги или используйте стрелки, чтобы менять приоритет.</p>
                    <div
                        id="queued-books-list"
                        class="space-y-2"
                        data-reorder-url="{{ route('books.queued.reorder') }}"
                        data-csrf="{{ csrf_token() }}"
                    >
                        @foreach($section['books'] as $book)
                            <div
                                class="queued-book card-hover flex items-center gap-2 !p-2.5 sm:!p-3 transition-opacity"
                                data-book-id="{{ $book->id }}"
                            >
                                <span draggable="true" class="drag-handle inline-flex h-8 w-7 shrink-0 cursor-grab items-center justify-center text-gray-300 hover:text-gray-500 active:cursor-grabbing" title="Перетащить">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <circle cx="7" cy="5" r="1.25"/><circle cx="13" cy="5" r="1.25"/>
                                        <circle cx="7" cy="10" r="1.25"/><circle cx="13" cy="10" r="1.25"/>
                                        <circle cx="7" cy="15" r="1.25"/><circle cx="13" cy="15" r="1.25"/>
                                    </svg>
                                </span>
                                <span class="priority-number w-6 shrink-0 text-center text-xs font-medium text-gray-400">{{ $loop->iteration }}</span>
                                <a href="{{ route('books.show', $book) }}" class="flex-1 min-w-0 flex items-center justify-between gap-3">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ $book->title }}</h3>
                                    @if($book->author)
                                        <span class="text-xs text-gray-400 truncate max-w-[40%] shrink-0 hidden sm:inline">{{ $book->author }}</span>
                                    @endif
                                </a>
                                <div class="flex items-center gap-0.5 shrink-0">
                                    <button type="button" class="move-up inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 disabled:opacity-25" title="Повысить приоритет" @disabled($loop->first)>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                    <button type="button" class="move-down inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 disabled:opacity-25" title="Понизить приоритет" @disabled($loop->last)>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    @include('partials.item-actions', [
                                        'edit' => route('books.edit', $book),
                                        'destroy' => route('books.destroy', $book),
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p id="priority-save-status" class="h-4 text-xs text-gray-400" aria-live="polite"></p>
                @else
                    <div class="space-y-3">
                        @foreach($section['books'] as $book)
                            <div class="card-hover list-row sm:items-center">
                                <a href="{{ route('books.show', $book) }}" class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900">{{ $book->title }}</h3>
                                    @if($book->author)<p class="text-sm text-gray-500 mt-0.5">{{ $book->author }}@if($book->year), {{ $book->year }}@endif</p>@endif
                                </a>
                                <div class="flex items-center gap-4 shrink-0">
                                    <span class="badge-gray hidden sm:inline">{{ $book->quotes_count }} цитат</span>
                                    @include('partials.item-actions', [
                                        'edit' => route('books.edit', $book),
                                        'destroy' => route('books.destroy', $book),
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-collapsible>
        @endif
    @endif
@endforeach

@if(! $hasBooks)
    <div class="card text-center py-12"><p class="text-gray-500">Добавьте первую книгу или перенесите из инбокса</p></div>
@endif

@push('scripts')
<script>
(() => {
    const list = document.getElementById('queued-books-list');
    const status = document.getElementById('priority-save-status');
    if (!list || !status) return;

    let draggedItem = null;
    let saveTimer = null;

    const items = () => [...list.querySelectorAll('.queued-book')];

    const updateControls = () => {
        const rows = items();
        rows.forEach((row, index) => {
            row.querySelector('.priority-number').textContent = String(index + 1);
            row.querySelector('.move-up').disabled = index === 0;
            row.querySelector('.move-down').disabled = index === rows.length - 1;
        });
    };

    const saveOrder = async () => {
        clearTimeout(saveTimer);
        status.textContent = 'Сохраняю…';

        try {
            const response = await fetch(list.dataset.reorderUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': list.dataset.csrf,
                },
                body: JSON.stringify({
                    book_ids: items().map((row) => Number(row.dataset.bookId)),
                }),
            });

            if (!response.ok) throw new Error('Не удалось сохранить порядок');

            status.textContent = 'Приоритет сохранён';
            saveTimer = setTimeout(() => {
                status.textContent = '';
            }, 1800);
        } catch (error) {
            status.textContent = 'Не удалось сохранить приоритет. Обновите страницу и повторите.';
            status.classList.add('text-red-600');
        }
    };

    list.addEventListener('click', (event) => {
        const row = event.target.closest('.queued-book');
        if (!row) return;

        if (event.target.closest('.move-up')) {
            const previous = row.previousElementSibling;
            if (previous) list.insertBefore(row, previous);
        } else if (event.target.closest('.move-down')) {
            const next = row.nextElementSibling;
            if (next) list.insertBefore(next, row);
        } else {
            return;
        }

        updateControls();
        saveOrder();
    });

    list.addEventListener('dragstart', (event) => {
        draggedItem = event.target.closest('.queued-book');
        if (!draggedItem) return;

        draggedItem.classList.add('opacity-40');
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', draggedItem.dataset.bookId);
    });

    list.addEventListener('dragover', (event) => {
        event.preventDefault();
        const target = event.target.closest('.queued-book');
        if (!draggedItem || !target || target === draggedItem) return;

        const box = target.getBoundingClientRect();
        const after = event.clientY > box.top + box.height / 2;
        list.insertBefore(draggedItem, after ? target.nextElementSibling : target);
        updateControls();
    });

    list.addEventListener('dragend', () => {
        if (!draggedItem) return;
        draggedItem.classList.remove('opacity-40');
        draggedItem = null;
        updateControls();
        saveOrder();
    });

    updateControls();
})();
</script>
@endpush
@endsection
