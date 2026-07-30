@extends('layouts.app')
@section('title', 'Книги')
@section('content')
<x-page-header title="Книги" subtitle="Библиотека и цитаты из книг">
    <x-slot:actions><a href="{{ route('books.create') }}" class="btn btn-primary">+ Книга</a></x-slot:actions>
</x-page-header>

@php
    $visible = $sections->filter(fn ($s) => $s['books']->isNotEmpty())->values();
    $bookTabs = $visible->map(fn ($s) => [
        'id' => 'books-'.$s['status'],
        'label' => $s['status'] === 'reading' ? 'Сейчас читаю' : $s['label'],
        'count' => $s['books']->count(),
    ])->all();
    $openStatus = $visible->first(fn ($s) => $s['status'] === 'reading')['status']
        ?? ($visible->first()['status'] ?? null);
    $bookActive = $openStatus ? 'books-'.$openStatus : null;
@endphp

@if($visible->isNotEmpty())
    <x-tabs :items="$bookTabs" :active="$bookActive">
        @foreach($visible as $section)
            @php $sid = 'books-'.$section['status']; @endphp
            <x-tab-panel :id="$sid" :show="$bookActive === $sid">
                @if($section['status'] === 'reading')
                    @include('partials.reading-books', [
                        'books' => $section['books'],
                        'showActions' => true,
                    ])
                @elseif($section['status'] === 'queued')
                    <div
                        id="queued-books-list"
                        class="divide-y divide-gray-100"
                        data-reorder-url="{{ route('books.queued.reorder') }}"
                        data-csrf="{{ csrf_token() }}"
                    >
                        @foreach($section['books'] as $book)
                            <div
                                class="queued-book group/row relative flex items-stretch rounded-lg hover:bg-gray-50 transition-colors transition-opacity"
                                data-book-id="{{ $book->id }}"
                            >
                                <a href="{{ route('books.show', $book) }}" class="absolute inset-0 z-0 rounded-lg" aria-label="{{ $book->title }}"></a>

                                <div class="relative z-10 flex shrink-0 items-center gap-1 py-1.5 pl-1">
                                    <span draggable="true" class="drag-handle inline-flex h-8 w-7 shrink-0 cursor-grab items-center justify-center text-gray-300 hover:text-gray-500 active:cursor-grabbing" title="Перетащить">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <circle cx="7" cy="5" r="1.25"/><circle cx="13" cy="5" r="1.25"/>
                                            <circle cx="7" cy="10" r="1.25"/><circle cx="13" cy="10" r="1.25"/>
                                            <circle cx="7" cy="15" r="1.25"/><circle cx="13" cy="15" r="1.25"/>
                                        </svg>
                                    </span>
                                    <span class="priority-number w-6 shrink-0 text-center text-xs font-medium text-gray-400 pointer-events-none">{{ $loop->iteration }}</span>
                                </div>

                                <div class="relative z-10 flex min-w-0 flex-1 items-center gap-3 pointer-events-none py-2.5 px-1">
                                    <h3 class="min-w-0 flex-1 text-sm font-medium text-gray-900 truncate group-hover/row:text-blue-600">{{ $book->title }}</h3>
                                    @if($book->author)
                                        <span class="text-xs text-gray-400 truncate max-w-[40%] shrink-0 hidden sm:inline">{{ $book->author }}</span>
                                    @endif
                                </div>

                                <div class="relative z-10 flex items-center gap-0.5 shrink-0 py-1.5 pr-1">
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
                    <div class="divide-y divide-gray-100">
                        @foreach($section['books'] as $book)
                            <x-list-row-card
                                :href="route('books.show', $book)"
                                :title="$book->title"
                                :subtitle="$book->author"
                            >
                                <span class="badge-gray hidden sm:inline shrink-0">{{ $book->quotes_count }} цитат</span>
                                @include('partials.item-actions', [
                                    'edit' => route('books.edit', $book),
                                    'destroy' => route('books.destroy', $book),
                                ])
                            </x-list-row-card>
                        @endforeach
                    </div>
                @endif
            </x-tab-panel>
        @endforeach
    </x-tabs>
@else
    <div class="card text-center py-12"><p class="text-gray-500">Добавьте первую книгу</p></div>
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
