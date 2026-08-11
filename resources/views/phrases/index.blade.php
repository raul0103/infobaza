@extends('layouts.app')
@section('title', 'Обороты речи')

@section('content')
<x-page-header title="Обороты речи" subtitle="Интересные обороты из книг и фильмов">
    <x-slot:actions>
        @if($totalPhrases > 0)
            <a href="{{ route('review.phrases') }}" class="btn btn-secondary">Повторять</a>
        @endif
        <a href="{{ route('phrases.create') }}" class="btn btn-primary">+ Оборот</a>
    </x-slot:actions>
</x-page-header>

@php
    $phraseTabs = collect();
    foreach ($bookSources as $source) {
        $phraseTabs->push([
            'id' => $source->id,
            'label' => $source->label,
            'count' => $source->phrases->count(),
        ]);
    }
    foreach ($movieSources as $source) {
        $phraseTabs->push([
            'id' => $source->id,
            'label' => $source->label,
            'count' => $source->phrases->count(),
        ]);
    }
    $phraseTabs->push([
        'id' => 'phrase-ungrouped',
        'label' => 'Без источника',
        'count' => $ungroupedPhrases->count(),
    ]);
    $phraseActive = $phraseTabs->firstWhere(fn ($tab) => $tab['count'] > 0)['id']
        ?? ($phraseTabs->first()['id'] ?? 'phrase-ungrouped');
@endphp

@if($totalPhrases === 0)
    <div class="card text-center py-12 text-gray-500">
        <p class="mb-3">Пока нет оборотов речи.</p>
        <a href="{{ route('phrases.create') }}" class="btn btn-primary">Добавить оборот</a>
    </div>
@else
    <x-tabs :items="$phraseTabs->all()" :active="$phraseActive">
        @foreach($bookSources as $source)
            <x-tab-panel :id="$source->id" :show="$phraseActive === $source->id">
                <x-slot:actions>
                    <a href="{{ route('phrases.create', ['book_id' => str_replace('book-', '', $source->id)]) }}" class="btn btn-primary text-sm">+ Оборот</a>
                </x-slot:actions>
                <div class="space-y-2">
                    @foreach($source->phrases as $phrase)
                        @include('phrases.card', ['phrase' => $phrase, 'showSource' => false])
                    @endforeach
                </div>
            </x-tab-panel>
        @endforeach

        @foreach($movieSources as $source)
            <x-tab-panel :id="$source->id" :show="$phraseActive === $source->id">
                <x-slot:actions>
                    <a href="{{ route('phrases.create', ['movie_id' => str_replace('movie-', '', $source->id)]) }}" class="btn btn-primary text-sm">+ Оборот</a>
                </x-slot:actions>
                <div class="space-y-2">
                    @foreach($source->phrases as $phrase)
                        @include('phrases.card', ['phrase' => $phrase, 'showSource' => false])
                    @endforeach
                </div>
            </x-tab-panel>
        @endforeach

        <x-tab-panel id="phrase-ungrouped" :show="$phraseActive === 'phrase-ungrouped'">
            <x-slot:actions>
                <a href="{{ route('phrases.create') }}" class="btn btn-primary text-sm">+ Оборот</a>
            </x-slot:actions>
            <div class="space-y-2">
                @forelse($ungroupedPhrases as $phrase)
                    @include('phrases.card', ['phrase' => $phrase])
                @empty
                    <div class="card text-center py-6 text-gray-500">Оборотов без источника пока нет</div>
                @endforelse
            </div>
        </x-tab-panel>
    </x-tabs>
@endif
@endsection
