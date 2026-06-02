@extends('layouts.app')
@section('title', 'Записи')
@section('content')
<x-page-header title="Записи" subtitle="Заметки и справочные материалы">
    <x-slot:actions><a href="{{ route('notes.create') }}" class="btn btn-primary">+ Запись</a></x-slot:actions>
</x-page-header>

<form class="card mb-6" method="GET">
    <div class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="label">Поиск</label>
            <input name="q" class="input" value="{{ $filters['q'] ?? '' }}" placeholder="По заголовку и тексту…">
        </div>
        <div class="w-48">
            <label class="label">Тема</label>
            <select name="topic_id" class="select" onchange="this.form.submit()">
                <option value="">Все темы</option>
                @include('partials.topic-select-options', ['groups' => $topicGroups, 'selected' => $filters['topic_id'] ?? null])
            </select>
        </div>
        <div class="w-48">
            <label class="label">Усвоение</label>
            <select name="mastery" class="select" onchange="this.form.submit()">
                <option value="">Все</option>
                @foreach(\App\Models\Note::masteryLabels() as $v => $l)
                    <option value="{{ $v }}" @selected(($filters['mastery'] ?? '') == (string)$v)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">Найти</button>
    </div>
</form>

<div class="space-y-3">
    @forelse($notes as $note)
        <div class="card-hover list-row">
            <a href="{{ route('notes.show', $note) }}" class="flex-1 min-w-0 block">
                <div class="flex justify-between items-start gap-4">
                    <h3 class="font-semibold text-gray-900">{{ $note->title }}</h3>
                    <div class="flex flex-wrap gap-2 shrink-0">
                        <span class="badge-gray">{{ \App\Models\Note::masteryLabels()[$note->mastery_level ?? 0] }}</span>
                        @if($note->topic)@include('partials.topic-badge', ['topic' => $note->topic])@endif
                    </div>
                </div>
                <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ Str::limit($note->content, 150) }}</p>
            </a>
            @include('partials.item-actions', [
                'edit' => route('notes.edit', $note),
                'destroy' => route('notes.destroy', $note),
            ])
        </div>
    @empty
        <div class="card text-center py-12"><p class="text-gray-500">Записей пока нет</p></div>
    @endforelse
</div>
<div class="mt-6">{{ $notes->links() }}</div>
@endsection
