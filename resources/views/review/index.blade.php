@extends('layouts.app')
@section('title', 'Повторение')
@section('content')
<x-page-header title="Повторение" subtitle="Интервальное повторение слов и экзамен по записям">
    <x-slot:actions>
        @if($dueQuestions > 0)
            <a href="{{ route('review.exam') }}" class="btn btn-secondary">Экзамен ({{ $dueQuestions }})</a>
        @endif
    </x-slot:actions>
</x-page-header>

@if($totalDueCards > 0)
<div class="card mb-6 border-l-4 border-l-blue-500">
    <p class="text-gray-700"><strong>{{ $totalDueCards }}</strong> карточек готовы к повторению по расписанию SRS.</p>
</div>
@endif

<div class="grid md:grid-cols-2 gap-4">
    @forelse($dictionaries as $dict)
        <a href="{{ route('review.session', $dict) }}" class="card-hover block p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-xl font-semibold text-gray-900">{{ $dict->name }}</div>
                    <div class="text-gray-500 mt-1">{{ $dict->entries_count }} карточек</div>
                </div>
                @if($dict->due_entries_count > 0)
                    <span class="badge-blue shrink-0">{{ $dict->due_entries_count }} к повтору</span>
                @endif
            </div>
            <div class="mt-4 text-blue-600 font-medium">Начать →</div>
        </a>
    @empty
        <div class="col-span-full card text-center py-12">
            <p class="text-gray-500">Сначала создайте словарь и добавьте слова</p>
            <a href="{{ route('dictionaries.create') }}" class="btn btn-primary mt-4">Создать словарь</a>
        </div>
    @endforelse
</div>
@endsection
