@extends('layouts.app')
@section('title', $joke->exists ? 'Редактировать анекдот' : 'Новый анекдот')

@section('content')
<x-form.shell
    :title="$joke->exists ? 'Редактировать анекдот' : 'Новый анекдот'"
    subtitle="Сохраните любимый анекдот"
    :action="$joke->exists ? route('jokes.update', $joke) : route('jokes.store')"
    :method="$joke->exists ? 'PUT' : 'POST'"
    :back="route('jokes.index')"
    wide
>
    <x-form.textarea name="text" label="Анекдот" :value="$joke->text" :rows="8" required />
    <x-form.input name="source" label="Откуда" :value="$joke->source" placeholder="Необязательно" />
</x-form.shell>

@if($joke->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', ['action' => route('jokes.destroy', $joke)])
    </div>
@endif
@endsection
