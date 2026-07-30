@extends('layouts.app')
@section('title', 'Фильмы')
@section('content')
<x-page-header title="Фильмы" subtitle="Что посмотреть и что уже смотрели">
    <x-slot:actions><a href="{{ route('movies.create') }}" class="btn btn-primary">+ Фильм</a></x-slot:actions>
</x-page-header>

@php
    $visible = $sections->filter(fn ($s) => $s['movies']->isNotEmpty())->values();
    $openStatus = $visible->first(fn ($s) => $s['status'] === 'watching')['status']
        ?? ($visible->first()['status'] ?? null);
    $movieTabs = $visible->map(fn ($s) => [
        'id' => 'movies-'.$s['status'],
        'label' => $s['label'],
        'count' => $s['movies']->count(),
    ])->all();
    $movieActive = $openStatus ? 'movies-'.$openStatus : null;
@endphp

@if($visible->isNotEmpty())
    <x-tabs :items="$movieTabs" :active="$movieActive">
        @foreach($visible as $section)
            @php $sid = 'movies-'.$section['status']; @endphp
            <x-tab-panel :id="$sid" :show="$movieActive === $sid">
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
            </x-tab-panel>
        @endforeach
    </x-tabs>
@else
    <div class="card text-center py-12 text-gray-500">Добавьте фильм</div>
@endif
@endsection
