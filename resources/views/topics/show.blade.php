@extends('layouts.app')
@section('title', $topic->name)
@section('content')
<x-page-header :title="$topic->name" :subtitle="$topic->description">
    <x-slot:actions>
        @if($topic->parent)
            <a href="{{ route('topics.show', $topic->parent) }}" class="btn btn-ghost text-sm">
                ↑ {{ $topic->parent->name }}
            </a>
        @endif
        <a href="{{ route('notes.create', ['topic_id' => $topic->id]) }}" class="btn btn-primary">+ Запись</a>
        <a href="{{ route('topics.edit', $topic) }}" class="btn btn-secondary">Изменить</a>
        @include('partials.delete-form', ['action' => route('topics.destroy', $topic)])
    </x-slot:actions>
</x-page-header>

@if($topic->parent)
    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
        <span>Раздел:</span>
        @include('partials.topic-badge', ['topic' => $topic->parent])
        <span>→</span>
        @include('partials.topic-badge', ['topic' => $topic])
    </div>
@endif

@if($topic->children->isNotEmpty())
    <div class="card mb-6">
        <h2 class="section-title mb-4">Подтемы</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($topic->children as $child)
                <a href="{{ route('topics.show', $child) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:border-blue-200 hover:bg-blue-50/50 transition">
                    {{ $child->name }}
                </a>
            @endforeach
        </div>
    </div>
@endif

<div class="grid md:grid-cols-2 gap-6">
    <div class="card">
        <h2 class="section-title mb-4">Записи</h2>
        @forelse($topic->notes as $n)
            <div class="list-item list-row sm:items-center">
                <a href="{{ route('notes.show', $n) }}" class="font-medium text-gray-900 hover:text-blue-600 flex-1">{{ $n->title }}</a>
                @include('partials.item-actions', [
                    'edit' => route('notes.edit', $n),
                    'destroy' => route('notes.destroy', $n),
                ])
            </div>
        @empty<p class="empty-state">Нет записей</p>@endforelse
    </div>
    <div class="card">
        <h2 class="section-title mb-4">Цитаты</h2>
        @forelse($topic->quotes as $q)
            <div class="list-item">
                <blockquote class="text-gray-600 italic border-l-4 border-gray-200 pl-4 mb-2">«{{ Str::limit($q->text, 120) }}»</blockquote>
                @include('partials.item-actions', [
                    'edit' => route('quotes.edit', $q),
                    'destroy' => route('quotes.destroy', $q),
                ])
            </div>
        @empty<p class="empty-state">Нет цитат</p>@endforelse
    </div>
</div>
@endsection
