@extends('layouts.app')
@section('title', 'Слово')

@section('content')
<x-form.shell
    :title="$entry->exists ? 'Редактировать слово' : 'Новое слово'"
    :subtitle="'Словарь: '.$dictionary->name"
    :action="$entry->exists ? route('dictionaries.entries.update', [$dictionary, $entry]) : route('dictionaries.entries.store', $dictionary)"
    :method="$entry->exists ? 'PUT' : 'POST'"
    :back="route('dictionaries.show', $dictionary)"
>
    <x-form.input name="term" label="Слово или фраза" :value="$entry->term" required />
    <x-form.textarea name="definition" label="Значение" :value="$entry->definition" :rows="4" required markdown />
    <x-form.textarea name="example" label="Пример использования" :value="$entry->example" :rows="2" markdown />

    <x-form.select name="group_id" label="Объединение" :hint="$dictionary->entryGroups->isEmpty() ? 'Пока нет объединений — можно создать на странице словаря.' : 'Необязательно. Привяжите слово к существующему объединению.'">
        @foreach($dictionary->entryGroups as $group)
            <option value="{{ $group->id }}" @selected((string) old('group_id', $entry->group_id) === (string) $group->id)>
                {{ $group->displayTitle() }}
            </option>
        @endforeach
    </x-form.select>

    @if($peerEntries->isNotEmpty())
        <div class="form-group">
            <label class="label">Синонимы</label>
            <select name="synonym_ids[]" class="select" multiple size="5">
                @foreach($peerEntries as $peer)
                    <option value="{{ $peer->id }}" @selected(collect($synonymIds)->contains($peer->id) || collect($synonymIds)->contains((string) $peer->id))>
                        {{ $peer->term }}
                    </option>
                @endforeach
            </select>
            <p class="hint">Связь двусторонняя. Ctrl+клик для нескольких</p>
            @error('synonym_ids')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            @error('synonym_ids.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label class="label">Антонимы</label>
            <select name="antonym_ids[]" class="select" multiple size="5">
                @foreach($peerEntries as $peer)
                    <option value="{{ $peer->id }}" @selected(collect($antonymIds)->contains($peer->id) || collect($antonymIds)->contains((string) $peer->id))>
                        {{ $peer->term }}
                    </option>
                @endforeach
            </select>
            <p class="hint">Связь двусторонняя. Ctrl+клик для нескольких</p>
            @error('antonym_ids')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            @error('antonym_ids.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    @endif
</x-form.shell>

@if($entry->exists)
    <div class="max-w-2xl">
        @include('partials.form-delete', [
            'action' => route('dictionaries.entries.destroy', [$dictionary, $entry]),
            'message' => 'Слово будет удалено из словаря.',
        ])
    </div>
@endif
@endsection
