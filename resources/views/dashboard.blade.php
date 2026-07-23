@extends('layouts.app')
@section('title', 'Главная')

@section('content')
@php
    $addWordUrl = $primaryDictionary
        ? route('dictionaries.entries.create', $primaryDictionary)
        : route('dictionaries.create');
    $addQuoteUrl = $readingBooks->isNotEmpty()
        ? route('quotes.create', ['book_id' => $readingBooks->first()->id])
        : route('books.index');
@endphp

<x-page-header title="Главная" subtitle="Слова, цитаты и то, что сейчас читаете">
    <x-slot:actions>
        @if($hasWords)
            <a href="{{ route('review.all') }}" class="btn btn-success">Повторить</a>
        @endif
        <a href="{{ $addQuoteUrl }}" class="btn btn-secondary">+ Цитата</a>
        <a href="{{ $addWordUrl }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Слово
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
            <button
                type="button"
                class="w-full text-left cursor-pointer group"
                data-kind="quote"
                data-text="{{ rawurlencode($randomQuote->text) }}"
                data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($randomQuote->text)->toHtml()) }}"
                data-context="{{ rawurlencode((string) $randomQuote->context) }}"
                data-context-html="{{ rawurlencode(\App\Support\Markdown::parse($randomQuote->context)->toHtml()) }}"
                data-page="{{ rawurlencode((string) $randomQuote->page) }}"
                data-character="{{ rawurlencode((string) $randomQuote->character) }}"
                data-source-label="{{ rawurlencode($sourceLabel ?? '') }}"
                data-source-url="{{ $sourceUrl }}"
                data-edit-url="{{ route('quotes.edit', $randomQuote) }}"
                onclick="openCardModal(this)"
            >
                <blockquote class="text-base sm:text-lg text-gray-800 italic leading-relaxed whitespace-pre-wrap group-hover:text-blue-700 transition">«{{ $randomQuote->text }}»</blockquote>
                @if($randomQuote->context)
                    <p class="mt-3 text-sm text-gray-500 whitespace-pre-wrap">{{ $randomQuote->context }}</p>
                @endif
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @if($randomQuote->page)<span class="badge-gray">Стр. {{ $randomQuote->page }}</span>@endif
                    @if($randomQuote->character)<span class="badge-gray">{{ $randomQuote->character }}</span>@endif
                    @if($sourceUrl)
                        <span class="link text-sm">{{ $sourceLabel }} →</span>
                    @endif
                </div>
            </button>
        @else
            <p class="empty-state">Цитат пока нет — добавьте из книги или фильма</p>
        @endif
    </div>

    <div class="card">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h2 class="section-title">Слова для повторения</h2>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('dictionaries.index') }}" class="link text-xs hidden sm:inline">Словари</a>
                @if($randomWords->isNotEmpty())
                    <a
                        href="{{ route('dashboard', array_filter([
                            'refresh_words' => 1,
                            'exclude_words' => $randomWords->pluck('id')->implode(','),
                            'keep_quote' => $randomQuote?->id,
                        ])) }}"
                        class="btn btn-secondary text-xs !px-2.5 !py-1.5"
                        title="Обновить слова"
                    >Обновить</a>
                @endif
            </div>
        </div>

        @if($randomWords->isNotEmpty())
            <div class="space-y-2">
                @foreach($randomWords as $entry)
                    <button
                        type="button"
                        class="w-full text-left card-hover !p-3 cursor-pointer"
                        data-term="{{ rawurlencode($entry->term) }}"
                        data-definition="{{ rawurlencode($entry->definition) }}"
                        data-definition-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->definition)->toHtml()) }}"
                        data-example="{{ rawurlencode((string) $entry->example) }}"
                        data-example-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->example)->toHtml()) }}"
                        data-dictionary-label="{{ rawurlencode($entry->dictionary?->name ?? '') }}"
                        data-dictionary-url="{{ $entry->dictionary ? route('dictionaries.show', $entry->dictionary) : '' }}"
                        data-edit-url="{{ $entry->dictionary ? route('dictionaries.entries.edit', [$entry->dictionary, $entry]) : '' }}"
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
    @include('partials.reading-books', ['books' => $readingBooks, 'showQuoteAdd' => true])
</div>
@endif

@if($recentNotes->isNotEmpty())
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h2 class="section-title">Последние записи</h2>
        <a href="{{ route('notes.index') }}" class="link">Все →</a>
    </div>
    @foreach($recentNotes as $n)
        <a href="{{ route('notes.show', $n) }}" class="list-item block hover:bg-gray-50 -mx-2 px-2 rounded-lg transition">
            <div class="font-medium text-gray-900">{{ $n->title }}</div>
            @if($n->topic)<span class="mt-1 inline-block">@include('partials.topic-badge', ['topic' => $n->topic])</span>@endif
        </a>
    @endforeach
</div>
@endif

@include('partials.entry-detail-modal')
@include('partials.card-detail-modal')
@endsection
