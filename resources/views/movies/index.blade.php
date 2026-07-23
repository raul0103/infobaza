@extends('layouts.app')
@section('title', 'Фильмы')
@section('content')
<x-page-header title="Фильмы" subtitle="Что посмотреть и что уже смотрели">
    <x-slot:actions><a href="{{ route('movies.create') }}" class="btn btn-primary">+ Фильм</a></x-slot:actions>
</x-page-header>

@php $hasMovies = $sections->sum(fn ($s) => $s['count']) > 0; @endphp

@if($hasMovies)
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($sections as $section)
            @if($section['count'] > 0)
                <x-category-card
                    :href="route('movies.status', $section['status'])"
                    :title="$section['label']"
                    :subtitle="$section['count'].' фильмов'"
                />
            @endif
        @endforeach
    </div>
@else
    <div class="card text-center py-12 text-gray-500">Добавьте фильм</div>
@endif
@endsection
