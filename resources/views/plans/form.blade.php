@extends('layouts.app')
@section('title', 'План')

@section('content')
<x-form.shell
    :title="$plan->exists ? 'Редактировать план' : 'Новый план'"
    subtitle="Идея или задача, которую хотите довести до конца"
    :action="$plan->exists ? route('plans.update', $plan) : route('plans.store')"
    :method="$plan->exists ? 'PUT' : 'POST'"
    :back="route('plans.index')"
    wide
>
    <x-form.input
        name="title"
        label="Название"
        :value="$plan->title"
        required
        placeholder="Кратко, о чём план"
        autofocus
    />

    <x-form.textarea
        name="description"
        label="Заметки"
        :value="$plan->description"
        :rows="5"
        hint="Детали, ссылки, что купить…"
    />

    <x-form.select name="status" label="Статус" :placeholder="false">
        @foreach(\App\Models\Plan::statusLabels() as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $plan->status ?? 'queued') === $value)>{{ $label }}</option>
        @endforeach
    </x-form.select>
</x-form.shell>

@if($plan->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', [
            'action' => route('plans.destroy', $plan),
            'message' => 'План и все его шаги будут удалены.',
        ])
    </div>
@endif
@endsection
