@extends('layouts.app')
@section('title', $memo->name)

@section('content')
<x-page-header :title="$memo->name" :subtitle="$memo->description">
    <x-slot:actions>
        <a href="{{ route('memos.entries.create', $memo) }}" class="btn btn-primary">+ Заметка</a>
        @include('partials.item-actions', [
            'edit' => route('memos.edit', $memo),
            'destroy' => route('memos.destroy', $memo),
        ])
    </x-slot:actions>
</x-page-header>

<form method="GET" action="{{ route('memos.show', $memo) }}" class="mb-4">
    <div class="flex gap-2">
        <input
            type="search"
            name="q"
            value="{{ $q }}"
            class="input flex-1"
            placeholder="Поиск в этой категории…"
        >
        <button type="submit" class="btn btn-secondary shrink-0">Найти</button>
        @if($q !== '')
            <a href="{{ route('memos.show', $memo) }}" class="btn btn-secondary shrink-0">Сброс</a>
        @endif
    </div>
    @if($q !== '')
        <p class="text-xs text-gray-500 mt-2">Показано {{ $memo->entries->count() }} из {{ $totalEntries }}</p>
    @endif
</form>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
    @forelse($memo->entries as $entry)
        <div class="card-hover !p-3 flex items-center gap-2">
            <button
                type="button"
                class="min-w-0 flex-1 text-left cursor-pointer"
                data-title="{{ rawurlencode($entry->title) }}"
                data-content="{{ rawurlencode((string) $entry->content) }}"
                data-content-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->content)->toHtml()) }}"
                data-edit-url="{{ route('memos.entries.edit', [$memo, $entry]) }}"
                onclick="openMemoModal(this)"
            >
                <div class="font-medium text-gray-900 truncate">{{ $entry->title }}</div>
                @if(filled($entry->content))
                    <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $entry->content }}</div>
                @endif
            </button>
            @include('partials.item-actions', [
                'edit' => route('memos.entries.edit', [$memo, $entry]),
                'destroy' => route('memos.entries.destroy', [$memo, $entry]),
            ])
        </div>
    @empty
        <div class="col-span-full card text-center py-12 text-gray-500">
            @if($q !== '')
                Ничего не найдено по «{{ $q }}»
            @else
                Добавьте первую заметку в эту категорию
            @endif
        </div>
    @endforelse
</div>

@include('partials.memo-detail-modal')
@endsection
