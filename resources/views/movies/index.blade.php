@extends('layouts.app')
@section('title', 'Фильмы')
@section('content')
<x-page-header title="Фильмы">
    <x-slot:actions><a href="{{ route('movies.create') }}" class="btn btn-primary">+ Фильм</a></x-slot:actions>
</x-page-header>

@php $hasMovies = $sections->sum(fn ($s) => $s['movies']->count()) > 0; @endphp

@foreach($sections as $section)
    @if($section['movies']->isNotEmpty())
        <x-collapsible
            :title="$section['label']"
            :count="$section['movies']->count()"
            :open="$section['status'] === 'watching'"
        >
            <div class="space-y-3">
                @foreach($section['movies'] as $movie)
                    <div class="card-hover list-row sm:items-center">
                        <a href="{{ route('movies.show', $movie) }}" class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900">{{ $movie->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                @if($movie->director){{ $movie->director }} · @endif
                                {{ $movie->quotes_count }} цитат
                            </p>
                        </a>
                        @include('partials.item-actions', [
                            'edit' => route('movies.edit', $movie),
                            'destroy' => route('movies.destroy', $movie),
                        ])
                    </div>
                @endforeach
            </div>
        </x-collapsible>
    @endif
@endforeach

@if(! $hasMovies)
    <div class="card text-center py-12 text-gray-500">Добавьте фильм или перенесите из инбокса</div>
@endif
@endsection
