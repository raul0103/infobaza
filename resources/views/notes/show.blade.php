@extends('layouts.app')
@section('title', $note->title)
@section('content')
<x-page-header :title="$note->title">
    <x-slot:actions>
        @if($note->topic)@include('partials.topic-badge', ['topic' => $note->topic])@endif
        <span class="badge-gray">{{ \App\Models\Note::masteryLabels()[$note->mastery_level] ?? '' }}</span>
        <a href="{{ route('notes.edit', $note) }}" class="btn btn-secondary">Изменить</a>
        @include('partials.delete-form', ['action' => route('notes.destroy', $note)])
    </x-slot:actions>
</x-page-header>
<div class="card prose prose-gray max-w-none mb-6">
    <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $note->content }}</div>
</div>
@if($note->recap)
<div class="card mb-6"><h2 class="section-title mb-2">Мой пересказ</h2><p class="text-gray-600 whitespace-pre-wrap">{{ $note->recap }}</p></div>
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
                <button class="text-sm text-red-600">Удалить</button>
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
