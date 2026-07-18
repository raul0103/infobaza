@extends('layouts.app')
@section('title', $group->exists ? 'Объединение слов' : 'Новое объединение')

@section('content')
<x-form.shell
    :title="$group->exists ? 'Редактировать объединение' : 'Объединить слова'"
    :subtitle="'Словарь: '.$dictionary->name"
    :action="$group->exists ? route('dictionaries.groups.update', [$dictionary, $group]) : route('dictionaries.groups.store', $dictionary)"
    :method="$group->exists ? 'PUT' : 'POST'"
    :back="route('dictionaries.show', $dictionary)"
    :files="true"
    :wide="true"
>
    <x-form.input name="title" label="Название объединения" :value="$group->title" hint="Необязательно. Например: синонимы, однокоренные, устойчивое выражение." />
    <x-form.textarea name="description" label="Описание" :value="$group->description" :rows="4" hint="Общее описание для всех слов в объединении." />

    <div class="form-group">
        <label class="label">Слова в объединении</label>
        <p class="hint mb-3">Выберите слова словаря, которые нужно объединить. Сами слова не удаляются.</p>
        <div class="max-h-72 overflow-y-auto rounded-lg border border-gray-200 divide-y divide-gray-100">
            @forelse($dictionary->entries as $entry)
                @php
                    $checked = in_array($entry->id, $selectedEntryIds, true) || in_array((string) $entry->id, $selectedEntryIds, true);
                    $inOtherGroup = $entry->group_id && (! $group->exists || (int) $entry->group_id !== (int) $group->id);
                @endphp
                <label class="flex items-start gap-3 px-3 py-3 hover:bg-gray-50 cursor-pointer">
                    <input
                        type="checkbox"
                        name="entry_ids[]"
                        value="{{ $entry->id }}"
                        class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        @checked($checked)
                    >
                    <span class="min-w-0">
                        <span class="block font-medium text-gray-900">{{ $entry->term }}</span>
                        <span class="block text-sm text-gray-500">{{ Str::limit($entry->definition, 100) }}</span>
                        @if($inOtherGroup)
                            <span class="badge-gray mt-1">Уже в другом объединении — при сохранении перенесётся сюда</span>
                        @endif
                    </span>
                </label>
            @empty
                <p class="px-3 py-6 text-sm text-gray-500 text-center">Сначала добавьте слова в словарь.</p>
            @endforelse
        </div>
        @error('entry_ids')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        @error('entry_ids.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="attachments" class="label">Скриншоты и файлы</label>
        <input
            type="file"
            name="attachments[]"
            id="attachments"
            class="input"
            multiple
            accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.txt,.doc,.docx,.xls,.xlsx,.zip,image/*"
        >
        <p class="hint">Можно выбрать несколько файлов. Максимум 10 МБ каждый.</p>
        @error('attachments')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        @error('attachments.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    @if($group->exists && $group->attachments->isNotEmpty())
        <div class="form-group">
            <p class="label">Уже прикреплённые файлы</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($group->attachments as $attachment)
                    <div class="rounded-lg border border-gray-200 p-3 flex gap-3 items-start">
                        @if($attachment->isImage())
                            <a href="{{ $attachment->url() }}" target="_blank" rel="noopener" class="shrink-0">
                                <img src="{{ $attachment->url() }}" alt="{{ $attachment->original_name }}" class="w-16 h-16 object-cover rounded-md border border-gray-100">
                            </a>
                        @else
                            <div class="w-16 h-16 rounded-md bg-gray-100 flex items-center justify-center text-xs text-gray-500 shrink-0">файл</div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <a href="{{ $attachment->url() }}" target="_blank" rel="noopener" class="link break-all">{{ $attachment->original_name }}</a>
                            <div class="mt-2">
                                {{-- Форма объявлена вне основной формы: вложенные <form> ломают submit --}}
                                <button
                                    type="submit"
                                    form="delete-attachment-{{ $attachment->id }}"
                                    class="btn btn-danger text-xs px-2 py-1 min-h-0"
                                    onclick="return confirm('Удалить файл?')"
                                >Удалить файл</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-form.shell>

@if($group->exists && $group->attachments->isNotEmpty())
    @foreach($group->attachments as $attachment)
        <form
            id="delete-attachment-{{ $attachment->id }}"
            method="POST"
            action="{{ route('dictionaries.groups.attachments.destroy', [$dictionary, $group, $attachment]) }}"
            class="hidden"
        >
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif

@if($group->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', [
            'action' => route('dictionaries.groups.destroy', [$dictionary, $group]),
            'message' => 'Объединение будет удалено. Слова останутся в словаре без объединения.',
        ])
    </div>
@endif
@endsection
