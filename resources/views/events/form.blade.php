@extends('layouts.app')
@section('title', 'Событие')

@section('content')
<x-form.shell
    :title="$event->exists ? 'Редактировать событие' : 'Новое событие'"
    :action="$event->exists ? route('events.update', $event) : route('events.store')"
    :method="$event->exists ? 'PUT' : 'POST'"
    :back="route('events.index')"
>
    <x-form.input name="title" label="Название" :value="$event->title" required />
    <x-form.textarea name="body" label="Описание" :value="$event->body" :rows="3" />
    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input name="starts_at" type="datetime-local" label="Начало"
            :value="old('starts_at', $event->starts_at?->format('Y-m-d\TH:i'))" required />
        <x-form.input name="ends_at" type="datetime-local" label="Конец"
            :value="old('ends_at', $event->ends_at?->format('Y-m-d\TH:i'))" />
    </div>
    <x-form.checkbox name="all_day" label="Событие на весь день" :checked="$event->all_day" />
    <x-form.input name="location" label="Место" :value="$event->location" placeholder="Адрес или название" />
</x-form.shell>

@if($event->exists)
    <div class="max-w-2xl">
        @include('partials.form-delete', ['action' => route('events.destroy', $event)])
    </div>
@endif
@endsection
