@extends('layouts.app')
@section('title', $movie->title)
@section('content')
<x-page-header :title="$movie->title">
    <x-slot:actions>
        <span class="badge-gray">{{ \App\Models\Movie::statusLabels()[$movie->status] ?? '' }}</span>
        <a href="{{ route('quotes.create', ['movie_id' => $movie->id]) }}" class="btn btn-primary">+ Цитата</a>
        @include('partials.item-actions', [
            'edit' => route('movies.edit', $movie),
            'destroy' => route('movies.destroy', $movie),
        ])
    </x-slot:actions>
</x-page-header>

<x-collapsible title="Цитаты" :count="$movie->quotes->count()">
    <x-slot:actions>
        <a href="{{ route('quotes.create', ['movie_id' => $movie->id]) }}" class="link">+ Добавить цитату</a>
    </x-slot:actions>

    @forelse($movie->quotes as $quote)
        @include('quotes.card', ['quote' => $quote])
    @empty
        <div class="card text-center py-10 text-gray-500">Нет цитат</div>
    @endforelse
</x-collapsible>
@endsection
