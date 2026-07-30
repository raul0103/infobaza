@extends('layouts.app')
@section('title', 'Повторение — факты')

@section('content')
<x-page-header title="Повторение фактов" subtitle="Выберите группу или повторите все" :back="route('facts.index')" back-label="К фактам" />

@if($totalFacts > 0)
    <a href="{{ route('review.facts.all') }}" class="card-hover !p-4 flex items-center gap-3 mb-4 group">
        <div class="min-w-0 flex-1">
            <div class="font-medium text-gray-900 group-hover:text-emerald-700">Все факты</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $totalFacts }} фактов</div>
        </div>
        <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    @if($groups->isNotEmpty())
        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-2 px-0.5">Группы</p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($groups as $group)
                <a href="{{ route('review.facts.group', $group) }}" class="card-hover !p-3 flex items-center gap-2 group">
                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-gray-900 group-hover:text-blue-600 truncate">{{ $group->name }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $group->facts_count }} фактов</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif
@else
    <div class="card text-center py-12 text-gray-500">
        <p class="mb-3">Пока нет фактов для повторения.</p>
        <a href="{{ route('facts.index') }}" class="btn btn-primary">К фактам</a>
    </div>
@endif
@endsection
