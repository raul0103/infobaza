@extends('layouts.app')
@section('title', $thought->exists ? 'Редактировать мысль' : 'Новая мысль')

@section('content')
<x-form.shell
    :title="$thought->exists ? 'Редактировать мысль' : 'Новая мысль о фильме'"
    :subtitle="$movie->title"
    :action="$thought->exists
        ? route('movies.thoughts.update', [$movie, $thought])
        : route('movies.thoughts.store', $movie)"
    :method="$thought->exists ? 'PUT' : 'POST'"
    :back="route('movies.show', $movie)"
    wide
>
    <x-form.textarea
        name="content"
        label="Моя мысль"
        :value="$thought->content"
        :rows="7"
        hint="Наблюдение, вывод, вопрос или идея, возникшая во время просмотра."
        required
        markdown
    />

    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input
            name="chapter"
            label="Эпизод / сцена"
            :value="$thought->chapter"
            hint="Например: серия 2 или название сцены."
        />
        <x-form.input
            name="page"
            label="Таймкод"
            :value="$thought->page"
            hint="Например: 01:12:30"
        />
    </div>
</x-form.shell>

@if($thought->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', [
            'action' => route('movies.thoughts.destroy', [$movie, $thought]),
            'message' => 'Мысль будет удалена.',
        ])
    </div>
@endif
@endsection
