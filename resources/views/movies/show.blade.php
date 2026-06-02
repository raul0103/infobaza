@extends('layouts.app')
@section('title', $movie->title)
@section('content')
<x-page-header :title="$movie->title">
    <x-slot:actions>
        <span class="badge-gray">{{ \App\Models\Movie::statusLabels()[$movie->status] ?? '' }}</span>
        <a href="{{ route('quotes.create', ['movie_id' => $movie->id]) }}" class="btn btn-primary">+ Цитата</a>
        <a href="{{ route('movies.edit', $movie) }}" class="btn btn-secondary">Изменить</a>
        @include('partials.delete-form', ['action' => route('movies.destroy', $movie)])
    </x-slot:actions>
</x-page-header>
<div class="space-y-4">
    @forelse($movie->quotes as $quote)
        <div class="card border-l-4 border-l-violet-400">
            <blockquote class="text-lg italic text-gray-800">«{{ $quote->text }}»</blockquote>
            @if($quote->character)<p class="text-sm text-gray-500 mt-2">— {{ $quote->character }}</p>@endif
            <div class="mt-4 pt-3 border-t border-gray-100">
                @include('partials.item-actions', [
                    'edit' => route('quotes.edit', $quote),
                    'destroy' => route('quotes.destroy', $quote),
                ])
            </div>
        </div>
    @empty
        <div class="card text-center py-10 text-gray-500">Нет цитат</div>
    @endforelse
</div>
@endsection
