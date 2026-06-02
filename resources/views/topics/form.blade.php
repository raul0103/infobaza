@extends('layouts.app')
@section('title', $topic->exists ? 'Редактировать тему' : 'Новая тема')

@section('content')
@php
    $isChild = (bool) old('parent_id', $topic->parent_id);
@endphp

<x-form.shell
    :title="$topic->exists ? 'Редактировать тему' : 'Новая тема'"
    :action="$topic->exists ? route('topics.update', $topic) : route('topics.store')"
    :method="$topic->exists ? 'PUT' : 'POST'"
    :back="route('topics.index')"
>
    <x-form.input name="name" label="Название" :value="$topic->name" required placeholder="Физика, Электрика…" />
    <x-form.select name="visibility" label="Видимость" :placeholder="false">
        <option value="private" @selected(old('visibility', $topic->visibility ?? 'private') === 'private')>Закрытая</option>
        <option value="public" @selected(old('visibility', $topic->visibility ?? 'private') === 'public')>Открытая</option>
    </x-form.select>

    <x-form.select name="parent_id" label="Основная тема" hint="Оставьте пустым, если это самостоятельный раздел. У подтемы своего цвета нет — используется только название.">
        @foreach($parents as $p)
            <option value="{{ $p->id }}" @selected(old('parent_id', $topic->parent_id) == $p->id)>{{ $p->name }}</option>
        @endforeach
    </x-form.select>

    <div id="topic-color-field" class="form-group {{ $isChild ? 'hidden' : '' }}">
        <label for="color" class="label">Цвет метки</label>
        <div class="flex items-center gap-3">
            <input type="color" name="color" id="color" class="h-11 w-16 rounded-lg border border-gray-300 cursor-pointer"
                value="{{ old('color', $topic->color ?? '#2563eb') }}">
            <span class="text-sm text-gray-500">Только для основных и отдельных тем</span>
        </div>
    </div>

    <x-form.textarea name="description" label="Описание" :value="$topic->description" :rows="4" />
</x-form.shell>

@if($topic->exists)
    <div class="max-w-2xl">
        @include('partials.form-delete', [
            'action' => route('topics.destroy', $topic),
            'message' => 'Тема будет удалена. Записи останутся, но без привязки к теме.',
        ])
    </div>
@endif
@endsection

@push('scripts')
<script>
(function () {
    const parentSelect = document.getElementById('parent_id');
    const colorField = document.getElementById('topic-color-field');
    if (!parentSelect || !colorField) return;

    function toggleColorField() {
        const isChild = parentSelect.value !== '';
        colorField.classList.toggle('hidden', isChild);
    }

    parentSelect.addEventListener('change', toggleColorField);
})();
</script>
@endpush
