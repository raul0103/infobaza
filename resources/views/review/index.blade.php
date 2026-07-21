@extends('layouts.app')
@section('title', 'Повторение')
@section('content')
<x-page-header title="Повторение" subtitle="Интервальное повторение слов и фактов" />

<div class="grid md:grid-cols-2 gap-4">
    @if($totalFacts > 0)
        <a href="{{ route('review.facts') }}" class="card-hover block p-6">
            <div class="text-xl font-semibold text-gray-900">Интересные факты</div>
            <div class="text-gray-500 mt-1">{{ $totalFacts }} карточек</div>
            <div class="mt-4 text-blue-600 font-medium">Начать →</div>
        </a>
    @endif
    @forelse($dictionaries as $dict)
        <a href="{{ route('review.session', $dict) }}" class="card-hover block p-6">
            <div class="text-xl font-semibold text-gray-900">{{ $dict->name }}</div>
            <div class="text-gray-500 mt-1">{{ $dict->entries_count }} карточек</div>
            <div class="mt-4 text-blue-600 font-medium">Начать →</div>
        </a>
    @empty
        @if($totalFacts === 0)
            <div class="col-span-full card text-center py-12">
                <p class="text-gray-500">Сначала создайте словарь и добавьте слова</p>
                <a href="{{ route('dictionaries.create') }}" class="btn btn-primary">Создать словарь</a>
            </div>
        @endif
    @endforelse
</div>
@endsection
