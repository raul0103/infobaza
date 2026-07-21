@extends('layouts.app')
@section('title', 'Главная')

@section('content')
<x-page-header title="Главная" subtitle="Обзор вашей базы знаний">
    <x-slot:actions>
        <a href="{{ route('guide.index') }}" class="btn btn-ghost hidden sm:inline-flex">Руководство</a>
        <a href="{{ route('notes.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Новая запись
        </a>
    </x-slot:actions>
</x-page-header>

<div class="grid lg:grid-cols-2 gap-4 mb-6">
    <div class="card">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h2 class="section-title">Случайная цитата</h2>
            @if($randomQuote)
                <a
                    href="{{ route('dashboard', array_filter([
                        'refresh_quote' => 1,
                        'exclude_quote' => $randomQuote->id,
                        'keep_words' => $randomWords->isNotEmpty() ? $randomWords->pluck('id')->implode(',') : null,
                    ])) }}"
                    class="btn btn-secondary text-xs !px-2.5 !py-1.5 shrink-0"
                    title="Обновить цитату"
                >Обновить</a>
            @endif
        </div>

        @if($randomQuote)
            @php
                $sourceLabel = $randomQuote->book?->title ?? $randomQuote->movie?->title;
                $sourceUrl = $randomQuote->book
                    ? route('books.show', $randomQuote->book)
                    : ($randomQuote->movie ? route('movies.show', $randomQuote->movie) : null);
            @endphp
            <blockquote class="text-base sm:text-lg text-gray-800 italic leading-relaxed whitespace-pre-wrap">«{{ $randomQuote->text }}»</blockquote>
            @if($randomQuote->context)
                <p class="mt-3 text-sm text-gray-500 whitespace-pre-wrap">{{ $randomQuote->context }}</p>
            @endif
            <div class="mt-4 flex flex-wrap items-center gap-2">
                @if($randomQuote->page)<span class="badge-gray">Стр. {{ $randomQuote->page }}</span>@endif
                @if($randomQuote->character)<span class="badge-gray">{{ $randomQuote->character }}</span>@endif
                @if($sourceUrl)
                    <a href="{{ $sourceUrl }}" class="link text-sm">{{ $sourceLabel }} →</a>
                @endif
            </div>
        @else
            <p class="empty-state">Цитат пока нет — добавьте из книги или фильма</p>
        @endif
    </div>

    <div class="card">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h2 class="section-title">Слова для повторения</h2>
            @if($randomWords->isNotEmpty())
                <a
                    href="{{ route('dashboard', array_filter([
                        'refresh_words' => 1,
                        'exclude_words' => $randomWords->pluck('id')->implode(','),
                        'keep_quote' => $randomQuote?->id,
                    ])) }}"
                    class="btn btn-secondary text-xs !px-2.5 !py-1.5 shrink-0"
                    title="Обновить слова"
                >Обновить</a>
            @endif
        </div>

        @if($randomWords->isNotEmpty())
            <div class="space-y-2">
                @foreach($randomWords as $entry)
                    <button
                        type="button"
                        class="w-full text-left card-hover !p-3 cursor-pointer"
                        data-term="{{ rawurlencode($entry->term) }}"
                        data-definition="{{ rawurlencode($entry->definition) }}"
                        data-example="{{ rawurlencode((string) $entry->example) }}"
                        data-dictionary-label="{{ rawurlencode($entry->dictionary?->name ?? '') }}"
                        data-dictionary-url="{{ $entry->dictionary ? route('dictionaries.show', $entry->dictionary) : '' }}"
                        onclick="openEntryModal(this)"
                    >
                        <div class="font-medium text-gray-900">{{ $entry->term }}</div>
                        <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $entry->definition }}</div>
                        @if($entry->dictionary)
                            <span class="badge-gray mt-2 inline-flex">{{ $entry->dictionary->name }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
        @else
            <p class="empty-state">Слов пока нет — добавьте в <a href="{{ route('dictionaries.index') }}" class="link">словари</a></p>
        @endif
    </div>
</div>

@if($readingBooks->isNotEmpty())
<div class="card mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="section-title">Сейчас читаю</h2>
        <a href="{{ route('books.index') }}" class="link">Все книги →</a>
    </div>
    @include('partials.reading-books', ['books' => $readingBooks])
</div>
@endif

<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h2 class="section-title">Последние записи</h2>
        <a href="{{ route('notes.index') }}" class="link">Все →</a>
    </div>
    @forelse($recentNotes as $n)
        <a href="{{ route('notes.show', $n) }}" class="list-item block hover:bg-gray-50 -mx-2 px-2 rounded-lg transition">
            <div class="font-medium text-gray-900">{{ $n->title }}</div>
            @if($n->topic)<span class="mt-1 inline-block">@include('partials.topic-badge', ['topic' => $n->topic])</span>@endif
        </a>
    @empty
        <p class="empty-state">Пока нет записей</p>
    @endforelse
</div>

@include('partials.entry-detail-modal')
@endsection
