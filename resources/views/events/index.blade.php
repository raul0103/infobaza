@extends('layouts.app')
@section('title', 'События')
@section('content')
<x-page-header title="События" subtitle="Календарь важных дел">
    <x-slot:actions><a href="{{ route('events.create') }}" class="btn btn-primary">+ Событие</a></x-slot:actions>
</x-page-header>

<div class="card mb-6">
    <h2 class="section-title mb-4">Предстоящие</h2>
    @forelse($upcoming as $e)
        <div class="list-item list-row">
            <div>
                <div class="font-medium text-gray-900">{{ $e->title }}</div>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $e->starts_at->format('d.m.Y H:i') }}
                    @if($e->location) · {{ $e->location }}@endif
                </p>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('events.edit', $e) }}" class="link">Изменить</a>
                @include('partials.delete-form', ['action' => route('events.destroy', $e)])
            </div>
        </div>
    @empty
        <p class="empty-state">Нет предстоящих событий</p>
    @endforelse
</div>

@if($past->isNotEmpty())
<div class="card">
    <h2 class="section-title mb-4 text-gray-500">Прошедшие</h2>
    @foreach($past as $e)
        <div class="py-2 text-sm text-gray-500">{{ $e->title }} — {{ $e->starts_at->format('d.m.Y') }}</div>
    @endforeach
</div>
@endif
@endsection
