@extends('layouts.app')
@section('title', $movie->exists ? 'Редактировать' : 'Новый фильм')

@section('content')
@php
    $back = route('movies.index');
@endphp
<x-form.shell
    :title="$movie->exists ? 'Редактировать фильм' : 'Новый фильм'"
    :action="$movie->exists ? route('movies.update', $movie) : route('movies.store')"
    :method="$movie->exists ? 'PUT' : 'POST'"
    :back="$back"
>
    <x-form.input name="title" label="Название" :value="$movie->title" required />
    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input name="director" label="Режиссёр" :value="$movie->director" />
        <x-form.input name="year" type="number" label="Год" :value="$movie->year" />
    </div>
    <x-form.select name="status" label="Статус" :placeholder="false">
        @foreach(\App\Models\Movie::statusLabels() as $v => $l)
            <option value="{{ $v }}" @selected(old('status', $movie->status ?? 'queued') == $v)>{{ $l }}</option>
        @endforeach
    </x-form.select>
    <x-form.textarea name="description" label="Заметки о фильме" :value="$movie->description" :rows="4" markdown />
</x-form.shell>

@if($movie->exists)
    <div class="max-w-2xl">
        @include('partials.form-delete', [
            'action' => route('movies.destroy', $movie),
            'message' => 'Фильм и все его цитаты будут удалены.',
        ])
    </div>
@endif
@endsection
