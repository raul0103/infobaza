@extends('layouts.app')
@section('title', $movie->title)
@section('content')
<x-page-header :title="$movie->title">
    <x-slot:actions>
        <span class="badge-gray">{{ \App\Models\Movie::statusLabels()[$movie->status] ?? '' }}</span>
        <a href="{{ route('quotes.create', ['movie_id' => $movie->id]) }}" class="btn btn-primary">+ Цитата</a>
        <a href="{{ route('tips.create', ['movie_id' => $movie->id]) }}" class="btn btn-secondary">+ Приём</a>
        @include('partials.item-actions', [
            'edit' => route('movies.edit', $movie),
            'destroy' => route('movies.destroy', $movie),
        ])
    </x-slot:actions>
</x-page-header>

@if($movie->description)
    <div class="card mb-6">
        <x-markdown :content="$movie->description" class="text-gray-600" />
    </div>
@endif

<x-collapsible title="Цитаты" :count="$movie->quotes->count()" :open="true">
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
</x-collapsible>

<x-collapsible title="Приёмы" :count="$movie->tips->count()" :open="$movie->tips->isNotEmpty()">
    <x-slot:actions>
        <a href="{{ route('tips.create', ['movie_id' => $movie->id]) }}" class="link">+ Добавить</a>
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
</x-collapsible>
@endsection
