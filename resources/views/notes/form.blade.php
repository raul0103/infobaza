@extends('layouts.app')
@section('title', $note->exists ? 'Редактировать' : 'Новая запись')

@section('content')
<x-form.shell
    :title="$note->exists ? 'Редактировать запись' : 'Новая запись'"
    subtitle="Заметки и материалы по темам"
    :action="$note->exists ? route('notes.update', $note) : route('notes.store')"
    :method="$note->exists ? 'PUT' : 'POST'"
    :back="route('notes.index')"
    wide
>
    <x-form.input name="title" label="Заголовок" :value="$note->title" required />
    <x-form.select name="topic_id" label="Тема">
        @include('partials.topic-select-options', ['groups' => $topicGroups, 'selected' => $note->topic_id])
    </x-form.select>
    <x-form.textarea name="content" label="Содержание" :value="$note->content" :rows="12" required markdown />
    <x-form.textarea name="recap" label="Пересказ своими словами" hint="Активное вспоминание" :value="$note->recap" :rows="3" markdown />
    @if(isset($allNotes) && $allNotes->isNotEmpty())
        <div class="form-group">
            <label class="label">Связанные записи</label>
            <select name="linked_note_ids[]" class="select" multiple size="5">
                @foreach($allNotes as $n)
                    <option value="{{ $n->id }}" @selected(collect(old('linked_note_ids', $note->linkedNotes->pluck('id')))->contains($n->id))>{{ $n->title }}</option>
                @endforeach
            </select>
            <p class="hint">Ctrl+клик для нескольких</p>
        </div>
    @endif
</x-form.shell>
@if($note->exists)
    <div class="max-w-4xl">@include('partials.form-delete', ['action' => route('notes.destroy', $note)])</div>
@endif
@endsection
