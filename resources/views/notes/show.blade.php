@extends('layouts.app')
@section('title', $note->title)
@section('content')
<x-page-header :title="$note->title">
    <x-slot:breadcrumb>
        <x-breadcrumb :items="array_values(array_filter([
            ['label' => 'Записи', 'url' => route('notes.index')],
            $note->topic ? ['label' => $note->topic->name, 'url' => route('topics.show', $note->topic)] : null,
            ['label' => $note->title],
        ]))" />
    </x-slot:breadcrumb>
    <x-slot:title-actions>
        @include('partials.item-actions', [
            'edit' => route('notes.edit', $note),
            'destroy' => route('notes.destroy', $note),
        ])
    </x-slot:title-actions>
</x-page-header>
@if($note->topic)
    <div class="mb-4">@include('partials.topic-badge', ['topic' => $note->topic])</div>
@endif
<div class="card mb-6">
    <x-markdown :content="$note->content" />
</div>
@if($note->recap)
<div class="card mb-6">
    <h2 class="section-title mb-2">Мой пересказ</h2>
    <x-markdown :content="$note->recap" class="text-gray-600" />
</div>
@endif
@if($note->linkedNotes->isNotEmpty())
<div class="card mb-6">
    <h2 class="section-title mb-3">Связанные записи</h2>
    @foreach($note->linkedNotes as $ln)
        <a href="{{ route('notes.show', $ln) }}" class="link block py-1">{{ $ln->title }}</a>
    @endforeach
</div>
@endif
<div class="card mb-6">
    <h2 class="section-title mb-4">Вопросы для повторения</h2>
    @forelse($note->questions as $q)
        <div class="list-item">
            <p class="font-medium">{{ $q->question }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($q->answer, 100) }}</p>
            <form method="POST" action="{{ route('notes.questions.destroy', [$note, $q]) }}" class="mt-2">@csrf @method('DELETE')
                <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-700" title="Удалить вопрос" aria-label="Удалить вопрос">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 7.5h12m-10.5 0v10.125A2.625 2.625 0 0010.125 20.25h3.75A2.625 2.625 0 0016.5 17.625V7.5m-6 0V6a1.5 1.5 0 011.5-1.5h0A1.5 1.5 0 0113.5 6v1.5"/>
                    </svg>
                </button>
            </form>
        </div>
    @empty
        <p class="empty-state">Добавьте вопросы — режим «Экзамен»</p>
    @endforelse
    <form method="POST" action="{{ route('notes.questions.store', $note) }}" class="mt-4 pt-4 border-t border-gray-100 space-y-3">@csrf
        <input name="question" class="input" placeholder="Вопрос" required>
        <textarea name="answer" class="textarea" rows="2" placeholder="Ответ" required></textarea>
        <button class="btn btn-secondary">+ Вопрос</button>
    </form>
</div>
@endsection
