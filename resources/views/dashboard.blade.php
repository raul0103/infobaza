@extends('layouts.app')
@section('title', 'Главная')

@section('content')
<x-page-header title="Главная" subtitle="Обзор вашей базы знаний">
    <x-slot:actions>
        <a href="{{ route('guide.index') }}" class="btn btn-ghost hidden sm:inline-flex">Руководство</a>
        <a href="{{ route('today.index') }}" class="btn btn-secondary">Сегодня</a>
        <a href="{{ route('notes.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Новая запись
        </a>
    </x-slot:actions>
</x-page-header>

@if($readingBooks->isNotEmpty())
<div class="card mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="section-title">Сейчас читаю</h2>
        <a href="{{ route('books.index') }}" class="link">Все книги →</a>
    </div>
    @foreach($readingBooks as $book)
        <a href="{{ route('books.show', $book) }}" class="block mb-3 last:mb-0">
            <div class="flex justify-between text-sm mb-1"><span class="font-medium text-gray-900">{{ $book->title }}</span><span class="text-gray-500">{{ $book->readingPercent() }}%</span></div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-emerald-500 rounded-full" style="width:{{ $book->readingPercent() }}%"></div></div>
        </a>
    @endforeach
</div>
@endif

<div class="grid md:grid-cols-2 gap-6">
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="section-title">Напоминания</h2>
            <a href="{{ route('reminders.index') }}" class="link">Все →</a>
        </div>
        @forelse($dueReminders as $r)
            <div class="list-item">
                <div class="font-medium text-gray-900">{{ $r->title }}</div>
                <div class="text-xs text-amber-600 mt-0.5">{{ $r->remind_at->format('d.m.Y H:i') }}</div>
            </div>
        @empty
            <p class="empty-state">Нет срочных напоминаний</p>
        @endforelse
    </div>

    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="section-title">Ближайшие события</h2>
            <a href="{{ route('events.index') }}" class="link">Все →</a>
        </div>
        @forelse($upcomingEvents as $e)
            <div class="list-item">
                <div class="font-medium text-gray-900">{{ $e->title }}</div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $e->starts_at->format('d.m.Y H:i') }}</div>
            </div>
        @empty
            <p class="empty-state">Нет предстоящих событий</p>
        @endforelse
    </div>

    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="section-title">Последние записи</h2>
            <a href="{{ route('notes.index') }}" class="link">Все →</a>
        </div>
        @forelse($recentNotes as $n)
            <a href="{{ route('notes.show', $n) }}" class="list-item block hover:bg-gray-50 -mx-2 px-2 rounded-lg transition">
                <div class="font-medium text-gray-900">{{ $n->title }}</div>
                @if($n->topic)<span class="mt-1 inline-block">@include('partials.topic-badge', ['topic' => $n->topic])</span>@endif
            </a>
        @empty
            <p class="empty-state">Пока нет записей</p>
        @endforelse
    </div>

    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="section-title">Дневник сегодня</h2>
            <a href="{{ route('journal.create') }}" class="link">Написать →</a>
        </div>
        @if($todayJournal)
            <a href="{{ route('journal.show', $todayJournal) }}" class="text-gray-600 text-sm leading-relaxed line-clamp-4 hover:text-blue-600 transition">
                {{ Str::limit($todayJournal->content, 200) }}
            </a>
        @else
            <p class="empty-state">Запись за сегодня ещё не создана</p>
        @endif
    </div>
</div>
@endsection
