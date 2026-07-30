@extends('layouts.app')
@section('title', 'Интересные факты')

@section('content')
<x-page-header title="Интересные факты" subtitle="Короткие заметки и любопытные сведения">
    <x-slot:actions>
        @if($totalFacts > 0)
            <a href="{{ route('review.facts') }}" class="btn btn-secondary">Повторять</a>
        @endif
        <a href="{{ route('fact-groups.create') }}" class="btn btn-secondary">+ Группа</a>
    </x-slot:actions>
</x-page-header>

@php
    $factTabs = $groups->map(fn ($g) => [
        'id' => 'fact-group-'.$g->id,
        'label' => $g->name,
        'count' => $g->facts->count(),
    ])->values();
    $factTabs->push([
        'id' => 'fact-ungrouped',
        'label' => 'Без группы',
        'count' => $ungroupedFacts->count(),
    ]);
    $factActive = $factTabs->first()['id'] ?? 'fact-ungrouped';
@endphp

<x-tabs :items="$factTabs->all()" :active="$factActive">
    @foreach($groups as $group)
        @php $gid = 'fact-group-'.$group->id; @endphp
        <x-tab-panel :id="$gid" :show="$factActive === $gid" :subtitle="$group->description">
            <x-slot:actions>
                <a href="{{ route('facts.create', ['fact_group_id' => $group->id]) }}" class="btn btn-primary text-sm">+ Факт</a>
                @include('partials.item-actions', [
                    'edit' => route('fact-groups.edit', $group),
                    'destroy' => route('fact-groups.destroy', $group),
                ])
            </x-slot:actions>

            <div class="space-y-2">
                @forelse($group->facts as $fact)
                    @include('facts.card', ['fact' => $fact])
                @empty
                    <div class="card text-center py-6 text-gray-500">В этой группе пока нет фактов</div>
                @endforelse
            </div>
        </x-tab-panel>
    @endforeach

    <x-tab-panel id="fact-ungrouped" :show="$factActive === 'fact-ungrouped'">
        <x-slot:actions>
            <a href="{{ route('facts.create') }}" class="btn btn-primary text-sm">+ Факт</a>
        </x-slot:actions>

        <div class="space-y-2">
            @forelse($ungroupedFacts as $fact)
                @include('facts.card', ['fact' => $fact])
            @empty
                <div class="card text-center py-6 text-gray-500">Фактов без группы пока нет</div>
            @endforelse
        </div>
    </x-tab-panel>
</x-tabs>
@endsection
