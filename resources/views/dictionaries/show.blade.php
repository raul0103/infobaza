@extends('layouts.app')
@section('title', $dictionary->name)

@section('content')
<x-page-header :title="$dictionary->name">
    <x-slot:breadcrumb>
        <x-breadcrumb :items="[
            ['label' => 'Словари', 'url' => route('dictionaries.index')],
            ['label' => $dictionary->name],
        ]" />
    </x-slot:breadcrumb>
    <x-slot:actions>
        <a href="{{ route('review.session', $dictionary) }}" class="btn btn-success">Повторение</a>
        <a href="{{ route('dictionaries.entries.create', $dictionary) }}" class="btn btn-primary">+ Слово</a>
    </x-slot:actions>
    <x-slot:title-actions>
        @include('partials.item-actions', [
            'edit' => route('dictionaries.edit', $dictionary),
            'destroy' => route('dictionaries.destroy', $dictionary),
        ])
    </x-slot:title-actions>
</x-page-header>

@php
    $hasDescription = filled($dictionary->description);
    $highlightGroupId = session('highlight_group');
    $dictTabs = collect([
        ['id' => 'words', 'label' => 'Слова', 'count' => $dictionary->entries->count()],
    ]);
    foreach ($dictionary->entryGroups as $group) {
        $dictTabs->push([
            'id' => 'group-'.$group->id,
            'label' => $group->displayTitle(),
            'count' => $group->entries->count(),
        ]);
    }
    if ($dictionary->entryGroups->isEmpty()) {
        $dictTabs->push(['id' => 'groups', 'label' => 'Объединения', 'count' => 0]);
    }
    $dictActive = $highlightGroupId
        ? 'group-'.$highlightGroupId
        : ($q !== '' ? 'words' : 'words');
@endphp

<x-tabs :items="$dictTabs->all()" :active="$dictActive">
    <x-tab-panel id="words" :show="$dictActive === 'words'">
        <x-slot:actions>
            @if($hasDescription)
                <button type="button" onclick="openModal('dictionary-description-modal')" class="btn btn-ghost text-sm">Описание</button>
            @endif
            @if($dictionary->language)
                <span class="badge-blue">{{ strtoupper($dictionary->language) }}</span>
            @endif
        </x-slot:actions>

        <form method="GET" action="{{ route('dictionaries.show', $dictionary) }}" class="mb-3">
            <div class="flex gap-2">
                <input
                    type="search"
                    name="q"
                    value="{{ $q }}"
                    class="input flex-1"
                    placeholder="Поиск по слову или значению…"
                    autofocus
                >
                <button type="submit" class="btn btn-secondary shrink-0">Найти</button>
                @if($q !== '')
                    <a href="{{ route('dictionaries.show', $dictionary) }}" class="btn btn-secondary shrink-0">Сброс</a>
                @endif
            </div>
            @if($q !== '')
                <p class="text-xs text-gray-500 mt-2">Показано {{ $dictionary->entries->count() }} из {{ $totalEntries }}</p>
            @endif
        </form>

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
                            onclick="if (!event.target.closest('a, button, form')) openEntryModal(this)"
                            data-term="{{ rawurlencode($entry->term) }}"
                            data-definition="{{ rawurlencode($entry->definition) }}"
                            data-definition-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->definition)->toHtml()) }}"
                            data-example="{{ rawurlencode((string) $entry->example) }}"
                            data-example-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->example)->toHtml()) }}"
                            data-edit-url="{{ route('dictionaries.entries.edit', [$dictionary, $entry]) }}">
                            <td class="px-4 sm:px-6 py-3 font-medium text-gray-900 align-top">
                                <div>{{ $entry->term }}</div>
                                @if($entry->group)
                                    <a href="#group-{{ $entry->group_id }}" class="badge-gray mt-1 inline-flex hover:bg-blue-50 hover:text-blue-700"
                                       onclick="event.preventDefault(); activateTab(this.closest('[data-tabs]'), 'group-{{ $entry->group_id }}')">
                                        {{ $entry->group->displayTitle() }}
                                    </a>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-3 text-gray-600 align-top">
                                {{ Str::limit($entry->definition, 80) }}
                            </td>
                            <td class="px-4 sm:px-6 py-3 text-left sm:text-right align-top whitespace-nowrap">
                                @include('partials.item-actions', [
                                    'edit' => route('dictionaries.entries.edit', [$dictionary, $entry]),
                                    'destroy' => route('dictionaries.entries.destroy', [$dictionary, $entry]),
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                @if($q !== '')
                                    Ничего не найдено по «{{ $q }}»
                                @else
                                    <p class="mb-3">Добавьте слова для изучения</p>
                                    <a href="{{ route('dictionaries.entries.create', $dictionary) }}" class="btn btn-primary">+ Слово</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </x-tab-panel>

    @forelse($dictionary->entryGroups as $group)
        @php $gid = 'group-'.$group->id; @endphp
        <x-tab-panel :id="$gid" :show="$dictActive === $gid" :subtitle="$group->description">
            <x-slot:actions>
                <a href="{{ route('dictionaries.groups.create', $dictionary) }}" class="link text-sm">+ Новое</a>
                @include('partials.item-actions', [
                    'edit' => route('dictionaries.groups.edit', [$dictionary, $group]),
                    'destroy' => route('dictionaries.groups.destroy', [$dictionary, $group]),
                ])
            </x-slot:actions>

            <p class="text-xs text-gray-500 mb-3">{{ $group->attachments->count() }} файлов</p>

            @if($group->entries->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-4">
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
        </x-tab-panel>
    @empty
        <x-tab-panel id="groups" :show="$dictActive === 'groups'">
            <p class="text-sm text-gray-500">
                <a href="{{ route('dictionaries.groups.create', $dictionary) }}" class="link">Объединить связанные слова</a>
                — общее описание, скриншоты и файлы.
            </p>
        </x-tab-panel>
    @endforelse
</x-tabs>

@if($hasDescription)
    <x-modal id="dictionary-description-modal" :title="$dictionary->name" size="lg">
        @if($dictionary->language)
            <p class="mb-4"><span class="badge-blue">{{ strtoupper($dictionary->language) }}</span></p>
        @endif
        <div class="markdown-body text-base text-gray-800">{!! \App\Support\Markdown::parse($dictionary->description) !!}</div>
    </x-modal>
@endif

@include('partials.entry-detail-modal')
@endsection
