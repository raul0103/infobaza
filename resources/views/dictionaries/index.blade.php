@extends('layouts.app')
@section('title', 'Словари')
@section('content')
@php($totalWords = $dictionaries->sum('entries_count'))

<x-page-header title="Словари" subtitle="Слова и фразы для изучения">
    <x-slot:actions>
        @if($totalWords > 0)
            <a href="{{ route('review.all') }}" class="btn btn-success">Повторить все</a>
        @endif
        <a href="{{ route('dictionaries.create') }}" class="btn btn-primary">+ Словарь</a>
    </x-slot:actions>
</x-page-header>

<form method="GET" class="mb-4">
    <div class="flex gap-2">
        <input
            type="search"
            name="q"
            value="{{ $q }}"
            class="input flex-1"
            placeholder="Поиск по слову или значению…"
            autofocus
        >
        <button type="submit" class="btn btn-secondary shrink-0">Найти</button>
        @if($q !== '')
            <a href="{{ route('dictionaries.index') }}" class="btn btn-secondary shrink-0">Сброс</a>
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
                    data-term="{{ rawurlencode($entry->term) }}"
                    data-definition="{{ rawurlencode($entry->definition) }}"
                    data-example="{{ rawurlencode((string) $entry->example) }}"
                    data-dictionary-label="{{ rawurlencode($entry->dictionary->name) }}"
                    data-dictionary-url="{{ route('dictionaries.show', $entry->dictionary) }}"
                    data-edit-url="{{ route('dictionaries.entries.edit', [$entry->dictionary, $entry]) }}"
                    onclick="openEntryModal(this)"
                >
                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-gray-900 truncate">{{ $entry->term }}</div>
                        <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $entry->definition }}</div>
                    </div>
                    <span class="badge-gray shrink-0">{{ $entry->dictionary->name }}</span>
                </button>
            @empty
                <div class="card text-center py-8 text-gray-500">Ничего не найдено по «{{ $q }}»</div>
            @endforelse
        </div>
    </div>
@endif

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
    @forelse($dictionaries as $dict)
        <x-category-card
            :href="route('dictionaries.show', $dict)"
            :title="$dict->name"
            :subtitle="$dict->entries_count.' слов'"
        >
            @if($dict->entries_count > 0)
                <a href="{{ route('review.session', $dict) }}" class="btn btn-success text-xs !px-2.5 !py-1.5 shrink-0">Повторение</a>
            @endif
            @include('partials.item-actions', [
                'edit' => route('dictionaries.edit', $dict),
                'destroy' => route('dictionaries.destroy', $dict),
            ])
        </x-category-card>
    @empty
        <div class="col-span-full card text-center py-12 text-gray-500">Создайте словарь для изучения терминов</div>
    @endforelse
</div>

@include('partials.entry-detail-modal')
@endsection
