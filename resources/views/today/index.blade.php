@extends('layouts.app')
@section('title', 'Сегодня')

@section('content')
<x-page-header title="Сегодня" subtitle="Ваш интеллектуальный фокус на день">
    <x-slot:actions>
        <a href="{{ route('review.index') }}" class="btn btn-success">Повторение</a>
    </x-slot:actions>
</x-page-header>

<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="stat-card">
        <div class="stat-value text-amber-500">{{ $streak }}</div>
        <div class="stat-label">дней подряд</div>
    </div>
    <div class="stat-card">
        <div class="stat-value {{ $studiedToday ? 'text-emerald-600' : 'text-gray-400' }}">{{ $studiedToday ? '✓' : '—' }}</div>
        <div class="stat-label">учёба сегодня</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $dueCards + $dueQuestions }}</div>
        <div class="stat-label">к повторению</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $inboxCount }}</div>
        <div class="stat-label">в инбоксе</div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <h2 class="section-title mb-4">Быстрый захват</h2>
        <form method="POST" action="{{ route('today.inbox') }}" class="space-y-3">
            @csrf
            <textarea name="content" class="textarea" rows="3" placeholder="Мысль, термин, идея — разберёте позже…" required></textarea>
            <button class="btn btn-primary w-full sm:w-auto">В инбокс</button>
        </form>
        @if($inboxCount)<a href="{{ route('inbox.index') }}" class="link mt-3 inline-block">Разобрать {{ $inboxCount }} →</a>@endif
    </div>

    <div class="card">
        <h2 class="section-title mb-4">Активность сегодня</h2>
        <ul class="text-sm space-y-2 text-gray-600">
            <li>Записи: <strong>{{ $todayActivity->notes_count }}</strong></li>
            <li>Карточки: <strong>{{ $todayActivity->cards_reviewed }}</strong></li>
            <li>Дневник: <strong>{{ $todayActivity->journal_count }}</strong></li>
            <li>Цитаты: <strong>{{ $todayActivity->quotes_count }}</strong></li>
            <li>Страницы: <strong>{{ $todayActivity->pages_read }}</strong></li>
        </ul>
    </div>
</div>

<div class="card mb-6">
    <h2 class="section-title mb-4">Урок дня</h2>
    <div class="grid md:grid-cols-3 gap-4">
        @if($lesson['quote'])
            <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                <p class="text-xs text-gray-400 uppercase mb-2">Цитата</p>
                <p class="italic text-gray-800 text-sm">«{{ Str::limit($lesson['quote']->text, 120) }}»</p>
                <p class="text-xs text-blue-600 mt-2">{{ $lesson['quote']->sourceLabel() }}</p>
            </div>
        @endif
        @if($lesson['note'])
            <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                <p class="text-xs text-gray-400 uppercase mb-2">Вспомнить</p>
                <a href="{{ route('notes.show', $lesson['note']) }}" class="font-medium text-gray-900 hover:text-blue-600">{{ $lesson['note']->title }}</a>
                <p class="text-sm text-gray-500 mt-2 line-clamp-3">{{ Str::limit($lesson['note']->content, 100) }}</p>
            </div>
        @endif
        @if($lesson['card'])
            <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                <p class="text-xs text-gray-400 uppercase mb-2">Слово</p>
                <p class="font-semibold text-gray-900">{{ $lesson['card']->term }}</p>
                <a href="{{ route('review.session', $lesson['card']->dictionary) }}" class="link mt-2 inline-block">Повторить →</a>
            </div>
        @endif
    </div>
</div>

<div class="flex flex-wrap gap-3">
    <a href="{{ route('review.index') }}" class="btn btn-primary">Карточки ({{ $dueCards }})</a>
    <a href="{{ route('review.exam') }}" class="btn btn-secondary">Экзамен ({{ $dueQuestions }})</a>
    <a href="{{ route('journal.create') }}" class="btn btn-secondary">Дневник</a>
</div>
@endsection
