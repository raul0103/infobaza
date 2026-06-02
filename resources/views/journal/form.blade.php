@extends('layouts.app')
@section('title', 'Дневник')

@section('content')
<x-form.shell
    :title="$entry->exists ? 'Редактировать запись' : 'Новая запись дня'"
    subtitle="Отчёт о дне, события и мысли"
    :action="$entry->exists ? route('journal.update', $entry) : route('journal.store')"
    :method="$entry->exists ? 'PUT' : 'POST'"
    :back="route('journal.index')"
    wide
>
    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input name="entry_date" type="date" label="Дата"
            :value="old('entry_date', $entry->entry_date?->format('Y-m-d') ?? today()->format('Y-m-d'))" required />
        <x-form.input name="mood" label="Настроение" :value="$entry->mood" placeholder="спокойно, продуктивно…" />
    </div>
    <x-form.input name="title" label="Заголовок" hint="Необязательно" :value="$entry->title" placeholder="Как прошёл день" />
    <x-form.textarea name="content" label="Содержание" :value="$entry->content" :rows="14" required
        placeholder="Что сделал, что узнал, важные события…" />
</x-form.shell>

@if($entry->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', ['action' => route('journal.destroy', $entry)])
    </div>
@endif
@endsection
