@extends('layouts.app')
@section('title', 'Слово')

@section('content')
<x-form.shell
    :title="$entry->exists ? 'Редактировать слово' : 'Новое слово'"
    :subtitle="'Словарь: '.$dictionary->name"
    :action="$entry->exists ? route('dictionaries.entries.update', [$dictionary, $entry]) : route('dictionaries.entries.store', $dictionary)"
    :method="$entry->exists ? 'PUT' : 'POST'"
    :back="route('dictionaries.show', $dictionary)"
>
    <x-form.input name="term" label="Слово или фраза" :value="$entry->term" required />
    <x-form.textarea name="definition" label="Значение" :value="$entry->definition" :rows="4" required />
    <x-form.select name="visibility" label="Видимость" :placeholder="false">
        <option value="private" @selected(old('visibility', $entry->visibility ?? 'private') === 'private')>Закрытая</option>
        <option value="public" @selected(old('visibility', $entry->visibility ?? 'private') === 'public')>Открытая</option>
    </x-form.select>
    <x-form.textarea name="example" label="Пример использования" :value="$entry->example" :rows="2" />
</x-form.shell>

@if($entry->exists)
    <div class="max-w-2xl">
        @include('partials.form-delete', [
            'action' => route('dictionaries.entries.destroy', [$dictionary, $entry]),
            'message' => 'Слово будет удалено из словаря.',
        ])
    </div>
@endif
@endsection
