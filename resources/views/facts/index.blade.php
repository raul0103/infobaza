@extends('layouts.app')
@section('title', 'Интересные факты')

@section('content')
<x-page-header title="Интересные факты" subtitle="Короткие заметки и любопытные сведения">
    <x-slot:actions>
        @if($totalFacts > 0)
            <a href="{{ route('review.facts') }}" class="btn btn-secondary">Повторять</a>
        @endif
        <a href="{{ route('fact-groups.create') }}" class="btn btn-secondary">+ Группа</a>
        <a href="{{ route('facts.create') }}" class="btn btn-primary">+ Факт</a>
    </x-slot:actions>
</x-page-header>

@foreach($groups as $group)
    <x-collapsible :title="$group->name" :count="$group->facts->count()">
        <x-slot:actions>
            @include('partials.item-actions', [
                'edit' => route('fact-groups.edit', $group),
                'destroy' => route('fact-groups.destroy', $group),
            ])
        </x-slot:actions>
        @if($group->description)
            <x-slot:subtitle>{{ $group->description }}</x-slot:subtitle>
        @endif

        @forelse($group->facts as $fact)
            @include('facts.card', ['fact' => $fact])
        @empty
            <div class="card text-center py-6 text-gray-500">В этой группе пока нет фактов</div>
        @endforelse
    </x-collapsible>
@endforeach

@if($ungroupedFacts->isNotEmpty())
    <x-collapsible title="Без группы" :count="$ungroupedFacts->count()">
        @foreach($ungroupedFacts as $fact)
            @include('facts.card', ['fact' => $fact])
        @endforeach
    </x-collapsible>
@endif

@if($groups->isEmpty() && $ungroupedFacts->isEmpty())
    <div class="card text-center py-12 text-gray-500">Пока нет интересных фактов</div>
@endif
@endsection
