@extends('layouts.app')
@section('title', $topic->name)
@section('content')
@php
    $topicCrumbs = array_values(array_filter([
        ['label' => 'Темы', 'url' => route('topics.index')],
        $topic->parent ? ['label' => $topic->parent->name, 'url' => route('topics.show', $topic->parent)] : null,
        ['label' => $topic->name],
    ]));
@endphp
<x-page-header :title="$topic->name" :subtitle="$topic->description">
    <x-slot:breadcrumb>
        <x-breadcrumb :items="$topicCrumbs" />
    </x-slot:breadcrumb>
    <x-slot:actions>
        <a href="{{ route('notes.create', ['topic_id' => $topic->id]) }}" class="btn btn-primary">+ Запись</a>
    </x-slot:actions>
    <x-slot:title-actions>
        @include('partials.item-actions', [
            'edit' => route('topics.edit', $topic),
            'destroy' => route('topics.destroy', $topic),
        ])
    </x-slot:title-actions>
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
@endsection
