@extends('layouts.app')
@section('title', $fact->exists ? 'Редактировать факт' : 'Новый факт')

@section('content')
<x-form.shell
    :title="$fact->exists ? 'Редактировать факт' : 'Новый факт'"
    subtitle="Интересное наблюдение или сведение"
    :action="$fact->exists ? route('facts.update', $fact) : route('facts.store')"
    :method="$fact->exists ? 'PUT' : 'POST'"
    :back="route('facts.index')"
    wide
>
    <x-form.input name="title" label="Заголовок" :value="$fact->title" placeholder="Необязательно" />
    <x-form.textarea name="text" label="Факт" :value="$fact->text" :rows="6" required />
    <x-form.input name="source" label="Источник" :value="$fact->source" placeholder="Книга, сайт, человек…" />
</x-form.shell>

@if($fact->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', ['action' => route('facts.destroy', $fact)])
    </div>
@endif
@endsection
