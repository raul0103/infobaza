@extends('layouts.app')
@section('title', $dictionary->name)

@section('content')
<x-page-header :title="$dictionary->name">
    <x-slot:actions>
        <a href="{{ route('review.session', $dictionary) }}" class="btn btn-success">Повторение</a>
        <a href="{{ route('dictionaries.entries.create', $dictionary) }}" class="btn btn-primary">+ Слово</a>
        <a href="{{ route('dictionaries.edit', $dictionary) }}" class="btn btn-secondary">Изменить</a>
        @include('partials.delete-form', ['action' => route('dictionaries.destroy', $dictionary)])
    </x-slot:actions>
</x-page-header>

@php
    $hasDescription = filled($dictionary->description);
    $previewLength = 200;
    $needsModal = $hasDescription && strlen($dictionary->description) > $previewLength;
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
                @php
                    $longDefinition = strlen($entry->definition) > 80;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 sm:px-6 py-4 font-medium text-gray-900 align-top">{{ $entry->term }}</td>
                    <td class="px-4 sm:px-6 py-4 text-gray-600 align-top">
                        @if($longDefinition)
                            <span>{{ Str::limit($entry->definition, 80) }}</span>
                            <button type="button"
                                class="link ml-1 text-xs"
                                onclick="openEntryModal({{ Js::from($entry->term) }}, {{ Js::from($entry->definition) }}, {{ Js::from($entry->example) }})">
                                ещё
                            </button>
                        @else
                            {{ $entry->definition }}
                        @endif
                    </td>
                    <td class="px-4 sm:px-6 py-4 text-left sm:text-right align-top whitespace-nowrap">
                        <a href="{{ route('dictionaries.entries.edit', [$dictionary, $entry]) }}" class="link">Изменить</a>
                        @include('partials.delete-form', ['action' => route('dictionaries.entries.destroy', [$dictionary, $entry]), 'compact' => true])
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">Добавьте слова для изучения</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

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
