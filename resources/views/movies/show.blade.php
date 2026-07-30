@extends('layouts.app')
@section('title', $movie->title)
@section('content')
@php
    $movieTab = $movie->quotes->isNotEmpty() || ($movie->tips->isEmpty() && $movie->thoughts->isEmpty())
        ? 'quotes'
        : ($movie->tips->isNotEmpty() ? 'tips' : 'thoughts');
@endphp
<x-page-header :title="$movie->title">
    <x-slot:breadcrumb>
        <x-breadcrumb :items="[
            ['label' => 'Фильмы', 'url' => route('movies.index')],
            ['label' => $movie->title],
        ]" />
    </x-slot:breadcrumb>
    <x-slot:title-actions>
        @include('partials.item-actions', [
            'edit' => route('movies.edit', $movie),
            'destroy' => route('movies.destroy', $movie),
        ])
    </x-slot:title-actions>
</x-page-header>

@if($movie->description)
    <div class="card mb-6">
        <x-markdown :content="$movie->description" class="text-gray-600" />
    </div>
@endif

<x-tabs
    :items="[
        ['id' => 'quotes', 'label' => 'Цитаты', 'count' => $movie->quotes->count()],
        ['id' => 'tips', 'label' => 'Приёмы', 'count' => $movie->tips->count()],
        ['id' => 'thoughts', 'label' => 'Мысли', 'count' => $movie->thoughts->count()],
    ]"
    :active="$movieTab"
>
    <x-tab-panel id="quotes" :show="$movieTab === 'quotes'">
        <x-slot:actions>
            <a href="{{ route('quotes.create', ['movie_id' => $movie->id]) }}" class="link">+ Добавить цитату</a>
        </x-slot:actions>

        <div class="space-y-2">
            @forelse($movie->quotes as $quote)
                @include('quotes.card', ['quote' => $quote])
            @empty
                <div class="card text-center py-10 text-gray-500">Нет цитат</div>
            @endforelse
        </div>
    </x-tab-panel>

    <x-tab-panel id="tips" :show="$movieTab === 'tips'">
        <x-slot:actions>
            <a href="{{ route('tips.create', ['movie_id' => $movie->id]) }}" class="link">+ Добавить приём</a>
        </x-slot:actions>

        <div class="space-y-2">
            @forelse($movie->tips as $tip)
                @include('tips.card', ['tip' => $tip])
            @empty
                <div class="card text-center py-8 text-gray-500">
                    Механики, трюки и советы из фильма.
                </div>
            @endforelse
        </div>
    </x-tab-panel>

    <x-tab-panel id="thoughts" :show="$movieTab === 'thoughts'">
        <x-slot:actions>
            <a href="{{ route('movies.thoughts.create', $movie) }}" class="link">+ Добавить мысль</a>
        </x-slot:actions>

        <div class="space-y-2">
            @forelse($movie->thoughts as $thought)
                @include('books.thoughts.card', ['thought' => $thought])
            @empty
                <div class="card text-center py-8 text-gray-500">
                    Записывайте свои выводы, вопросы и идеи по мере просмотра.
                </div>
            @endforelse
        </div>
    </x-tab-panel>
</x-tabs>
@endsection
