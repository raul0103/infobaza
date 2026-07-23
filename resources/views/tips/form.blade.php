@extends('layouts.app')
@section('title', 'Приём')

@section('content')
@php
    $lockedBook = null;
    $lockedMovie = null;
    $bookId = old('book_id', $tip->book_id);
    $movieId = old('movie_id', $tip->movie_id);

    if ($bookId) {
        $lockedBook = $books->firstWhere('id', (int) $bookId);
    } elseif ($movieId) {
        $lockedMovie = $movies->firstWhere('id', (int) $movieId);
    }

    $back = $lockedBook
        ? route('books.show', $lockedBook)
        : ($lockedMovie
            ? route('movies.show', $lockedMovie)
            : (url()->previous() !== url()->current() ? url()->previous() : route('dashboard')));
@endphp

<x-form.shell
    :title="$tip->exists ? 'Редактировать приём' : 'Новый приём'"
    :subtitle="$lockedBook ? 'Книга: '.$lockedBook->title : ($lockedMovie ? 'Фильм: '.$lockedMovie->title : 'Механика, трюк или совет')"
    :action="$tip->exists ? route('tips.update', $tip) : route('tips.store')"
    :method="$tip->exists ? 'PUT' : 'POST'"
    :back="$back"
    wide
>
    @if($lockedBook)
        <input type="hidden" name="book_id" value="{{ $lockedBook->id }}">
        <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700">
            Книга: <span class="font-medium">{{ $lockedBook->title }}</span>
        </div>
    @elseif($lockedMovie)
        <input type="hidden" name="movie_id" value="{{ $lockedMovie->id }}">
        <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700">
            Фильм: <span class="font-medium">{{ $lockedMovie->title }}</span>
        </div>
    @else
        <div class="grid sm:grid-cols-2 gap-5">
            <x-form.select name="book_id" label="Книга">
                @foreach($books as $b)
                    <option value="{{ $b->id }}" @selected(old('book_id', $tip->book_id) == $b->id)>{{ $b->title }}</option>
                @endforeach
            </x-form.select>
            <x-form.select name="movie_id" label="Фильм">
                @foreach($movies as $m)
                    <option value="{{ $m->id }}" @selected(old('movie_id', $tip->movie_id) == $m->id)>{{ $m->title }}</option>
                @endforeach
            </x-form.select>
        </div>
    @endif

    <x-form.input
        name="title"
        label="Название"
        :value="$tip->title"
        placeholder="Например: двойной прыжок, финт корпусом…"
        hint="Коротко — как называется приём или совет."
    />

    <x-form.textarea
        name="content"
        label="Описание"
        :value="$tip->content"
        :rows="7"
        required
        autofocus
        hint="Как это работает, когда применять, нюансы."
        markdown
    />

    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input name="chapter" label="Глава / сцена" :value="$tip->chapter" />
        <x-form.input name="page" label="Страница / таймкод" :value="$tip->page" />
    </div>
</x-form.shell>

@if($tip->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', [
            'action' => route('tips.destroy', $tip),
            'message' => 'Приём будет удалён.',
        ])
    </div>
@endif
@endsection
