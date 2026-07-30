@extends('layouts.app')
@section('title', $fact->exists ? 'Редактировать факт' : 'Новый факт')

@section('content')
@php
    $backHash = $fact->fact_group_id
        ? 'fact-group-'.$fact->fact_group_id
        : (($preselectedGroupId ?? null) ? 'fact-group-'.$preselectedGroupId : 'fact-ungrouped');
@endphp
<x-form.shell
    :title="$fact->exists ? 'Редактировать факт' : 'Новый факт'"
    subtitle="Интересное наблюдение или сведение"
    :action="$fact->exists ? route('facts.update', $fact) : route('facts.store')"
    :method="$fact->exists ? 'PUT' : 'POST'"
    :back="route('facts.index').'#'.$backHash"
    wide
>
    <x-form.input name="title" label="Заголовок" :value="$fact->title" placeholder="Необязательно" />

    @if($fact->exists)
        <x-form.select name="fact_group_id" label="Группа" hint="Необязательно.">
            @foreach($groups as $group)
                <option value="{{ $group->id }}" @selected((string) old('fact_group_id', $fact->fact_group_id) === (string) $group->id)>
                    {{ $group->name }}
                </option>
            @endforeach
        </x-form.select>
    @elseif($preselectedGroupId)
        <input type="hidden" name="fact_group_id" value="{{ $preselectedGroupId }}">
    @endif

    <x-form.textarea name="text" label="Факт" :value="$fact->text" :rows="6" required markdown />
    <x-form.input name="source" label="Источник" :value="$fact->source" placeholder="Книга, сайт, человек…" />
</x-form.shell>

@if($fact->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', ['action' => route('facts.destroy', $fact)])
    </div>
@endif
@endsection
