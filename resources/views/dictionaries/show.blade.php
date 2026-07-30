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
        : 'words';
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

        @include('dictionaries.partials.entries-table', [
            'entries' => $dictionary->entries,
            'showGroupBadge' => true,
            'q' => $q,
        ])
    </x-tab-panel>

    @forelse($dictionary->entryGroups as $group)
        @php $gid = 'group-'.$group->id; @endphp
        <x-tab-panel :id="$gid" :show="$dictActive === $gid">
            <x-slot:actions>
                <a href="{{ route('dictionaries.groups.create', $dictionary) }}" class="link text-sm">+ Новое</a>
                @include('partials.item-actions', [
                    'edit' => route('dictionaries.groups.edit', [$dictionary, $group]),
                    'destroy' => route('dictionaries.groups.destroy', [$dictionary, $group]),
                ])
            </x-slot:actions>

            @if(filled($group->description))
                <div class="markdown-body text-sm text-gray-600 mb-3">{!! \App\Support\Markdown::parse($group->description) !!}</div>
            @endif

            @include('dictionaries.partials.entries-table', [
                'entries' => $group->entries,
                'emptyEditUrl' => route('dictionaries.groups.edit', [$dictionary, $group]),
            ])

            @if($group->attachments->isNotEmpty())
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-3">Файлы</p>
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
