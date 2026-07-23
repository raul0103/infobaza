@extends('layouts.app')
@section('title', 'Словарь')

@section('content')
<x-form.shell
    :title="$dictionary->exists ? 'Редактировать словарь' : 'Новый словарь'"
    :action="$dictionary->exists ? route('dictionaries.update', $dictionary) : route('dictionaries.store')"
    :method="$dictionary->exists ? 'PUT' : 'POST'"
    :back="route('dictionaries.index')"
>
    <x-form.input name="name" label="Название" :value="$dictionary->name" required placeholder="Английский B1, Термины CCTV…" />
    <x-form.input name="language" label="Язык" :value="$dictionary->language" placeholder="ru, en, de" />
    <x-form.textarea name="description" label="Описание" :value="$dictionary->description" :rows="3" markdown />
</x-form.shell>

@if($dictionary->exists)
    <div class="max-w-2xl">
        @include('partials.form-delete', [
            'action' => route('dictionaries.destroy', $dictionary),
            'message' => 'Словарь и все слова внутри него будут удалены.',
        ])
    </div>
@endif
@endsection
