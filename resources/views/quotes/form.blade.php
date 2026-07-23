@extends('layouts.app')
@section('title', 'Цитата')

@section('content')
@php
    $lockedBook = null;
    $lockedMovie = null;
    $bookId = old('book_id', $quote->book_id);
    $movieId = old('movie_id', $quote->movie_id);

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
    :title="$quote->exists ? 'Редактировать цитату' : 'Новая цитата'"
    :subtitle="$lockedBook ? 'Книга: '.$lockedBook->title : ($lockedMovie ? 'Фильм: '.$lockedMovie->title : 'Привяжите к книге или фильму')"
    :action="$quote->exists ? route('quotes.update', $quote) : route('quotes.store')"
    :method="$quote->exists ? 'PUT' : 'POST'"
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
                    <option value="{{ $b->id }}" @selected(old('book_id', $quote->book_id) == $b->id)>{{ $b->title }}</option>
                @endforeach
            </x-form.select>
            <x-form.select name="movie_id" label="Фильм">
                @foreach($movies as $m)
                    <option value="{{ $m->id }}" @selected(old('movie_id', $quote->movie_id) == $m->id)>{{ $m->title }}</option>
                @endforeach
            </x-form.select>
        </div>
    @endif

    <x-form.textarea name="text" label="Текст цитаты" :value="$quote->text" :rows="5" required autofocus />

    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input name="page" label="Страница" :value="$quote->page" />
        <x-form.input name="character" label="Персонаж / автор реплики" :value="$quote->character" />
    </div>

    <x-form.textarea name="context" label="Контекст" :value="$quote->context" :rows="2" hint="Когда и при каких обстоятельствах" />
</x-form.shell>

@if($quote->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', ['action' => route('quotes.destroy', $quote)])
    </div>
@endif
@endsection
