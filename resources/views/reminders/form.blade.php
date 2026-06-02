@extends('layouts.app')
@section('title', 'Напоминание')

@section('content')
<x-form.shell
    :title="$reminder->exists ? 'Редактировать напоминание' : 'Новое напоминание'"
    :action="$reminder->exists ? route('reminders.update', $reminder) : route('reminders.store')"
    :method="$reminder->exists ? 'PUT' : 'POST'"
    :back="route('reminders.index')"
>
    <x-form.input name="title" label="Заголовок" :value="$reminder->title" required />
    <x-form.textarea name="body" label="Описание" :value="$reminder->body" :rows="3" />
    <x-form.input name="remind_at" type="datetime-local" label="Когда напомнить"
        :value="old('remind_at', $reminder->remind_at?->format('Y-m-d\TH:i'))" required />
</x-form.shell>

@if($reminder->exists)
    <div class="max-w-2xl">
        @include('partials.form-delete', ['action' => route('reminders.destroy', $reminder)])
    </div>
@endif
@endsection
