@extends('layouts.app')
@section('title', $book->exists ? 'Редактировать книгу' : 'Новая книга')

@section('content')
<x-form.shell
    :title="$book->exists ? 'Редактировать книгу' : 'Новая книга'"
    :action="$book->exists ? route('books.update', $book) : route('books.store')"
    :method="$book->exists ? 'PUT' : 'POST'"
    :back="route('books.index')"
    wide
>
    <x-form.input name="title" label="Название" :value="$book->title" required />
    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input name="author" label="Автор" :value="$book->author" />
        <x-form.input name="year" type="number" label="Год издания" :value="$book->year" />
    </div>
    <x-form.select name="status" label="Статус" :placeholder="false">
        @foreach(\App\Models\Book::statusLabels() as $v => $l)
            <option value="{{ $v }}" @selected(old('status', $book->status ?? 'queued') == $v)>{{ $l }}</option>
        @endforeach
    </x-form.select>
    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input name="current_page" type="number" label="Текущая страница" :value="$book->current_page" />
        <x-form.input name="total_pages" type="number" label="Всего страниц" :value="$book->total_pages" />
    </div>
    @if($book->exists)
        <x-form.input name="pages_added" type="number" label="Прочитал сегодня (страниц)" hint="Прибавится к «Текущая страница»" />
    @endif
    <div class="grid sm:grid-cols-2 gap-5">
        <x-form.input name="started_at" type="date" label="Начал читать" :value="old('started_at', $book->started_at?->format('Y-m-d'))" />
        <x-form.input name="finished_at" type="date" label="Закончил" :value="old('finished_at', $book->finished_at?->format('Y-m-d'))" />
    </div>
    <x-form.textarea name="description" label="Описание" :value="$book->description" :rows="3" markdown />
</x-form.shell>
@if($book->exists)
    <div class="max-w-4xl">@include('partials.form-delete', ['action' => route('books.destroy', $book)])</div>
@endif
@endsection
