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

@php
    $factTabs = $groups->map(fn ($g) => [
        'id' => 'fact-group-'.$g->id,
        'label' => $g->name,
        'count' => $g->facts->count(),
    ])->values();
    if ($ungroupedFacts->isNotEmpty()) {
        $factTabs->push([
            'id' => 'fact-ungrouped',
            'label' => 'Без группы',
            'count' => $ungroupedFacts->count(),
        ]);
    }
    $factActive = $factTabs->first()['id'] ?? null;
@endphp

@if($factTabs->isNotEmpty())
    <x-tabs :items="$factTabs->all()" :active="$factActive">
        @foreach($groups as $group)
            @php $gid = 'fact-group-'.$group->id; @endphp
            <x-tab-panel :id="$gid" :show="$factActive === $gid" :subtitle="$group->description">
                <x-slot:actions>
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

        @if($ungroupedFacts->isNotEmpty())
            <x-tab-panel id="fact-ungrouped" :show="$factActive === 'fact-ungrouped'">
                <div class="space-y-2">
                    @foreach($ungroupedFacts as $fact)
                        @include('facts.card', ['fact' => $fact])
                    @endforeach
                </div>
            </x-tab-panel>
        @endif
    </x-tabs>
@else
    <div class="card text-center py-12 text-gray-500">Пока нет интересных фактов</div>
@endif
@endsection
