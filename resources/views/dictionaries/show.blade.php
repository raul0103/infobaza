@extends('layouts.app')
@section('title', $dictionary->name)

@section('content')
<x-page-header :title="$dictionary->name">
    <x-slot:actions>
        <a href="{{ route('review.session', $dictionary) }}" class="btn btn-success">Повторение</a>
        <a href="{{ route('dictionaries.groups.create', $dictionary) }}" class="btn btn-secondary">Объединить слова</a>
        <a href="{{ route('dictionaries.entries.create', $dictionary) }}" class="btn btn-primary">+ Слово</a>
        @include('partials.item-actions', [
            'edit' => route('dictionaries.edit', $dictionary),
            'destroy' => route('dictionaries.destroy', $dictionary),
        ])
    </x-slot:actions>
</x-page-header>

@php
    $hasDescription = filled($dictionary->description);
    $previewLength = 200;
    $needsModal = $hasDescription && strlen($dictionary->description) > $previewLength;
    $highlightGroupId = session('highlight_group');
@endphp

<div class="card mb-6 {{ $hasDescription ? 'cursor-pointer hover:border-blue-200 transition' : '' }}"
    @if($hasDescription) onclick="if (!event.target.closest('a, button')) openModal('dictionary-description-modal')" @endif>
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <h2 class="section-title">Описание словаря</h2>
                @if($dictionary->language)
                    <span class="badge-blue">{{ strtoupper($dictionary->language) }}</span>
                @endif
                <span class="badge-gray">{{ $dictionary->entries->count() }} слов</span>
                @if($dictionary->entryGroups->isNotEmpty())
                    <span class="badge-gray">{{ $dictionary->entryGroups->count() }} объединений</span>
                @endif
            </div>

            @if($hasDescription)
                <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-wrap">{{ $needsModal ? Str::limit($dictionary->description, $previewLength) : $dictionary->description }}</div>
                @if($needsModal)
                    <button type="button" onclick="event.stopPropagation(); openModal('dictionary-description-modal')" class="link mt-3 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Читать полностью
                    </button>
                @endif
            @else
                <p class="text-sm text-gray-500">Описание не добавлено.</p>
                <a href="{{ route('dictionaries.edit', $dictionary) }}" class="link mt-2 inline-block">Добавить описание →</a>
            @endif
        </div>

        @if($hasDescription)
            <button type="button" onclick="event.stopPropagation(); openModal('dictionary-description-modal')" class="btn btn-secondary shrink-0" title="Открыть описание">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Описание
            </button>
        @endif
    </div>
</div>

@if($hasDescription)
    <x-modal id="dictionary-description-modal" :title="$dictionary->name" size="lg">
        @if($dictionary->language)
            <p class="mb-4"><span class="badge-blue">{{ strtoupper($dictionary->language) }}</span></p>
        @endif
        <div class="whitespace-pre-wrap text-base text-gray-800">{{ $dictionary->description }}</div>
    </x-modal>
@endif

@if($dictionary->entryGroups->isEmpty())
    <x-collapsible title="Объединения слов" :count="0">
        <x-slot:actions>
            <a href="{{ route('dictionaries.groups.create', $dictionary) }}" class="link">+ Новое объединение</a>
        </x-slot:actions>
        <div class="card text-center py-8 text-gray-500">
            Можно объединить связанные слова, добавить общее описание, скриншоты и файлы.
        </div>
    </x-collapsible>
@else
    @foreach($dictionary->entryGroups as $group)
        <x-collapsible
            :id="'group-'.$group->id"
            :title="$group->displayTitle()"
            :count="$group->entries->count()"
            :open="(int) $highlightGroupId === (int) $group->id"
        >
            <x-slot:actions>
                @include('partials.item-actions', [
                    'edit' => route('dictionaries.groups.edit', [$dictionary, $group]),
                    'destroy' => route('dictionaries.groups.destroy', [$dictionary, $group]),
                ])
            </x-slot:actions>
            @if(filled($group->description))
                <x-slot:subtitle>{{ $group->description }}</x-slot:subtitle>
            @endif

            <p class="text-xs text-gray-500 -mt-1">{{ $group->attachments->count() }} файлов</p>

            @if($group->entries->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach($group->entries as $groupedEntry)
                        <span class="badge-blue">{{ $groupedEntry->term }}</span>
                    @endforeach
                </div>
            @endif

            @if($group->attachments->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($group->attachments as $attachment)
                        <a href="{{ $attachment->url() }}" target="_blank" rel="noopener" class="block rounded-lg border border-gray-200 overflow-hidden hover:border-blue-300 transition">
                            @if($attachment->isImage())
                                <img src="{{ $attachment->url() }}" alt="{{ $attachment->original_name }}" class="w-full h-28 object-cover bg-gray-50">
                            @else
                                <div class="h-28 flex items-center justify-center bg-gray-50 text-xs text-gray-500 px-2 text-center">
                                    {{ $attachment->original_name }}
                                </div>
                            @endif
                            <div class="px-2 py-1.5 text-xs text-gray-600 truncate border-t border-gray-100">{{ $attachment->original_name }}</div>
                        </a>
                    @endforeach
                </div>
            @endif
        </x-collapsible>
    @endforeach
@endif

<x-collapsible title="Все слова" :count="$dictionary->entries->count()" :open="true">
    <div class="card overflow-hidden p-0">
        <div class="table-scroll">
        <table class="w-full min-w-[32rem] text-left text-sm">
            <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
                <tr>
                    <th class="px-4 sm:px-6 py-3 font-medium">Слово</th>
                    <th class="px-4 sm:px-6 py-3 font-medium">Значение</th>
                    <th class="px-4 sm:px-6 py-3 w-28 sm:w-32"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($dictionary->entries as $entry)
                    <tr class="hover:bg-gray-50 cursor-pointer"
                        data-term="{{ rawurlencode($entry->term) }}"
                        data-definition="{{ rawurlencode($entry->definition) }}"
                        data-example="{{ rawurlencode((string) $entry->example) }}"
                        onclick="if (!event.target.closest('a, button, form')) openEntryModal(decodeURIComponent(this.dataset.term), decodeURIComponent(this.dataset.definition), decodeURIComponent(this.dataset.example))">
                        <td class="px-4 sm:px-6 py-4 font-medium text-gray-900 align-top">
                            <div>{{ $entry->term }}</div>
                            @if($entry->group)
                                <a href="#group-{{ $entry->group_id }}" class="badge-gray mt-1 inline-flex hover:bg-blue-50 hover:text-blue-700"
                                   onclick="document.getElementById('group-{{ $entry->group_id }}')?.setAttribute('open','')">
                                    {{ $entry->group->displayTitle() }}
                                </a>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-gray-600 align-top">
                            {{ Str::limit($entry->definition, 80) }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-left sm:text-right align-top whitespace-nowrap">
                            @include('partials.item-actions', [
                                'edit' => route('dictionaries.entries.edit', [$dictionary, $entry]),
                                'destroy' => route('dictionaries.entries.destroy', [$dictionary, $entry]),
                            ])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">Добавьте слова для изучения</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</x-collapsible>

<x-modal id="entry-detail-modal" title="Слово" size="lg">
    <div id="entry-modal-definition" class="whitespace-pre-wrap text-gray-800 mb-4"></div>
    <div id="entry-modal-example-wrap" class="hidden border-t border-gray-100 pt-4">
        <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Пример</p>
        <p id="entry-modal-example" class="text-gray-600 italic whitespace-pre-wrap"></p>
    </div>
</x-modal>

@push('scripts')
<script>
function openEntryModal(term, definition, example) {
    document.getElementById('entry-detail-modal-title').textContent = term;
    document.getElementById('entry-modal-definition').textContent = definition;
    const exampleWrap = document.getElementById('entry-modal-example-wrap');
    const exampleEl = document.getElementById('entry-modal-example');
    if (example) {
        exampleWrap.classList.remove('hidden');
        exampleEl.textContent = example;
    } else {
        exampleWrap.classList.add('hidden');
        exampleEl.textContent = '';
    }
    openModal('entry-detail-modal');
}
</script>
@endpush
@endsection
