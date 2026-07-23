@extends('layouts.app')
@section('title', $label)

@section('content')
<x-page-header :title="$label">
    <x-slot:actions>
        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Все статусы</a>
        <a href="{{ route('movies.create', ['status' => $status]) }}" class="btn btn-primary">+ Фильм</a>
    </x-slot:actions>
</x-page-header>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
    @forelse($movies as $movie)
        @php
            $meta = collect([
                $movie->year,
                $movie->director,
                $movie->quotes_count.' цитат',
            ])->filter()->implode(' · ');
        @endphp
        <x-category-card
            :href="route('movies.show', $movie)"
            :title="$movie->title"
            :subtitle="$meta"
        >
            @include('partials.item-actions', [
                'edit' => route('movies.edit', $movie),
                'destroy' => route('movies.destroy', $movie),
            ])
        </x-category-card>
    @empty
        <div class="col-span-full card text-center py-12 text-gray-500">В этом статусе пока нет фильмов</div>
    @endforelse
</div>
@endsection
