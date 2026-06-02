@extends('layouts.app')
@section('title', 'Напоминания')
@section('content')
<x-page-header title="Напоминания">
    <x-slot:actions><a href="{{ route('reminders.create') }}" class="btn btn-primary">+ Напоминание</a></x-slot:actions>
</x-page-header>

<div class="card mb-6">
    <h2 class="section-title mb-4">Активные</h2>
    @forelse($pending as $r)
        <div class="list-item list-row {{ $r->remind_at->isPast() ? 'bg-amber-50 -mx-2 px-4 rounded-lg border-l-4 border-l-amber-400' : '' }}">
            <div>
                <div class="font-medium text-gray-900">{{ $r->title }}</div>
                @if($r->body)<p class="text-sm text-gray-500 mt-0.5">{{ $r->body }}</p>@endif
                <p class="text-xs text-gray-400 mt-1">{{ $r->remind_at->format('d.m.Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <form method="POST" action="{{ route('reminders.complete', $r) }}">@csrf
                    <button type="submit" class="btn btn-ghost text-emerald-600" title="Выполнено">✓</button>
                </form>
                <a href="{{ route('reminders.edit', $r) }}" class="link">Изменить</a>
                @include('partials.delete-form', ['action' => route('reminders.destroy', $r)])
            </div>
        </div>
    @empty
        <p class="empty-state">Нет активных напоминаний</p>
    @endforelse
</div>

@if($completed->isNotEmpty())
<div class="card bg-gray-50/50">
    <h2 class="section-title mb-4 text-gray-500">Выполненные</h2>
    @foreach($completed as $r)
        <div class="py-2 text-sm text-gray-400 line-through">{{ $r->title }}</div>
    @endforeach
</div>
@endif
@endsection
