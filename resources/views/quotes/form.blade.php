@extends('layouts.app')
@section('title', 'Цитата')

@section('content')
<x-form.shell
    :title="$quote->exists ? 'Редактировать цитату' : 'Новая цитата'"
    subtitle="Привяжите к книге, фильму или теме"
    :action="$quote->exists ? route('quotes.update', $quote) : route('quotes.store')"
    :method="$quote->exists ? 'PUT' : 'POST'"
    :back="url()->previous() !== url()->current() ? url()->previous() : route('quotes.index')"
    wide
>
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

    <x-form.textarea name="text" label="Текст цитаты" :value="$quote->text" :rows="5" required />

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
