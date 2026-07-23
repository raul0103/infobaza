@extends('layouts.app')
@section('title', 'Заметка')

@section('content')
<x-form.shell
    :title="$entry->exists ? 'Редактировать заметку' : 'Новая заметка'"
    :subtitle="'Категория: '.$memo->name"
    :action="$entry->exists ? route('memos.entries.update', [$memo, $entry]) : route('memos.entries.store', $memo)"
    :method="$entry->exists ? 'PUT' : 'POST'"
    :back="route('memos.show', $memo)"
    :wide="true"
>
    <x-form.input name="title" label="Заголовок" :value="$entry->title" required placeholder="Кратко о сути…" />
    <x-form.textarea name="content" label="Текст" :value="$entry->content" :rows="12" placeholder="Мысль, совет, наблюдение…" markdown />
</x-form.shell>

@if($entry->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', [
            'action' => route('memos.entries.destroy', [$memo, $entry]),
            'message' => 'Заметка будет удалена.',
        ])
    </div>
@endif
@endsection
