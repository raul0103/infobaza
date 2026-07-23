@extends('layouts.app')
@section('title', 'Категория')

@section('content')
<x-form.shell
    :title="$memo->exists ? 'Редактировать категорию' : 'Новая категория'"
    :action="$memo->exists ? route('memos.update', $memo) : route('memos.store')"
    :method="$memo->exists ? 'PUT' : 'POST'"
    :back="route('memos.index')"
>
    <x-form.input name="name" label="Название" :value="$memo->name" required placeholder="Советы, Мысли, Привычки…" />
    <x-form.textarea name="description" label="Описание" :value="$memo->description" :rows="3" />
</x-form.shell>

@if($memo->exists)
    <div class="max-w-2xl">
        @include('partials.form-delete', [
            'action' => route('memos.destroy', $memo),
            'message' => 'Категория и все заметки внутри неё будут удалены.',
        ])
    </div>
@endif
@endsection
