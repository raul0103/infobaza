@extends('layouts.app')
@section('title', 'Заметки')
@section('content')
<x-page-header title="Заметки" subtitle="Мысли, советы и наблюдения на жизнь">
    <x-slot:actions>
        <a href="{{ route('memos.create') }}" class="btn btn-primary">+ Категория</a>
    </x-slot:actions>
</x-page-header>

<form method="GET" class="mb-4">
    <div class="flex gap-2">
        <input
            type="search"
            name="q"
            value="{{ $q }}"
            class="input flex-1"
            placeholder="Поиск по заголовку или тексту…"
            autofocus
        >
        <button type="submit" class="btn btn-secondary shrink-0">Найти</button>
        @if($q !== '')
            <a href="{{ route('memos.index') }}" class="btn btn-secondary shrink-0">Сброс</a>
        @endif
    </div>
</form>

@if($q !== '')
    <div class="mb-6">
        <p class="text-sm text-gray-500 mb-3">Найдено: {{ $searchResults->count() }}{{ $searchResults->count() >= 100 ? '+' : '' }}</p>
        <div class="space-y-2">
            @forelse($searchResults as $entry)
                <button
                    type="button"
                    class="card-hover !p-3 flex items-center gap-3 w-full text-left cursor-pointer"
                    data-title="{{ rawurlencode($entry->title) }}"
                    data-content="{{ rawurlencode((string) $entry->content) }}"
                    data-category-label="{{ rawurlencode($entry->memo->name) }}"
                    data-category-url="{{ route('memos.show', $entry->memo) }}"
                    data-edit-url="{{ route('memos.entries.edit', [$entry->memo, $entry]) }}"
                    onclick="openMemoModal(this)"
                >
                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-gray-900 truncate">{{ $entry->title }}</div>
                        @if(filled($entry->content))
                            <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $entry->content }}</div>
                        @endif
                    </div>
                    <span class="badge-gray shrink-0">{{ $entry->memo->name }}</span>
                </button>
            @empty
                <div class="card text-center py-8 text-gray-500">Ничего не найдено по «{{ $q }}»</div>
            @endforelse
        </div>
    </div>
@endif

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
    @forelse($memos as $memo)
        <x-category-card
            :href="route('memos.show', $memo)"
            :title="$memo->name"
            :subtitle="$memo->entries_count.' заметок'"
        >
            @include('partials.item-actions', [
                'edit' => route('memos.edit', $memo),
                'destroy' => route('memos.destroy', $memo),
            ])
        </x-category-card>
    @empty
        <div class="col-span-full card text-center py-12 text-gray-500">Создайте категорию — например «Советы», «Мысли», «Привычки»</div>
    @endforelse
</div>

@include('partials.memo-detail-modal')
@endsection
