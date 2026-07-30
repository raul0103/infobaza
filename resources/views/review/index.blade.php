@extends('layouts.app')
@section('title', 'Повторение')

@section('content')
<x-page-header title="Повторение" subtitle="Выберите словарь или повторите все слова" :back="route('dictionaries.index')" back-label="К словарям" />

@if($totalWords > 0)
    <a href="{{ route('review.all') }}" class="card-hover !p-4 flex items-center gap-3 mb-4 group">
        <div class="min-w-0 flex-1">
            <div class="font-medium text-gray-900 group-hover:text-emerald-700">Все словари</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $totalWords }} слов</div>
        </div>
        <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    @if($dictionaries->isNotEmpty())
        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-2 px-0.5">Категории</p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($dictionaries as $dict)
                <a href="{{ route('review.session', $dict) }}" class="card-hover !p-3 flex items-center gap-2 group">
                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-gray-900 group-hover:text-blue-600 truncate">{{ $dict->name }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $dict->entries_count }} слов</div>
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
        <p class="mb-3">Пока нет слов для повторения.</p>
        <a href="{{ route('dictionaries.index') }}" class="btn btn-primary">К словарям</a>
    </div>
@endif
@endsection
