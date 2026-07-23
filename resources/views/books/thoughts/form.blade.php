@extends('layouts.app')
@section('title', $thought->exists ? 'Редактировать мысль' : 'Новая мысль')

@section('content')
<x-form.shell
    :title="$thought->exists ? 'Редактировать мысль' : 'Новая мысль о книге'"
    :subtitle="$book->title"
    :action="$thought->exists
        ? route('books.thoughts.update', [$book, $thought])
        : route('books.thoughts.store', $book)"
    :method="$thought->exists ? 'PUT' : 'POST'"
    :back="route('books.show', $book)"
    wide
>
    <x-form.textarea
        name="content"
        label="Моя мысль"
        :value="$thought->content"
        :rows="7"
        hint="Наблюдение, вывод, вопрос или идея, возникшая во время чтения."
        required
        markdown
    />

    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input
            name="chapter"
            label="Глава"
            :value="$thought->chapter"
            hint="Например: Глава 3 или название главы."
        />
        <x-form.input
            name="page"
            label="Страница"
            :value="$thought->page"
            hint="Можно указать страницу или диапазон."
        />
    </div>
</x-form.shell>

@if($thought->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', [
            'action' => route('books.thoughts.destroy', [$book, $thought]),
            'message' => 'Мысль будет удалена.',
        ])
    </div>
@endif
@endsection
