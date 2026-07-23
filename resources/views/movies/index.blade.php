@extends('layouts.app')
@section('title', 'Фильмы')
@section('content')
<x-page-header title="Фильмы" subtitle="Что посмотреть и что уже смотрели">
    <x-slot:actions><a href="{{ route('movies.create') }}" class="btn btn-primary">+ Фильм</a></x-slot:actions>
</x-page-header>

@php
    $hasMovies = $sections->sum(fn ($s) => $s['movies']->count()) > 0;
    $openStatus = $sections->first(fn ($s) => $s['status'] === 'watching' && $s['movies']->isNotEmpty())
        ? 'watching'
        : ($sections->first(fn ($s) => $s['movies']->isNotEmpty())['status'] ?? null);
@endphp

@foreach($sections as $section)
    @if($section['movies']->isNotEmpty())
        <x-collapsible
            :title="$section['label']"
            :count="$section['movies']->count()"
            :open="$section['status'] === $openStatus"
        >
            <x-slot:actions>
                <a href="{{ route('movies.create', ['status' => $section['status']]) }}" class="link">+ Добавить</a>
            </x-slot:actions>

            <div class="divide-y divide-gray-100">
                @foreach($section['movies'] as $movie)
                    @php
                        $meta = collect([$movie->year, $movie->director])->filter()->implode(' · ');
                    @endphp
                    <x-list-row-card
                        :href="route('movies.show', $movie)"
                        :title="$movie->title"
                        :subtitle="$meta"
                    >
                        @include('partials.item-actions', [
                            'edit' => route('movies.edit', $movie),
                            'destroy' => route('movies.destroy', $movie),
                        ])
                    </x-list-row-card>
                @endforeach
            </div>
        </x-collapsible>
    @endif
@endforeach

@if(! $hasMovies)
    <div class="card text-center py-12 text-gray-500">Добавьте фильм</div>
@endif
@endsection
