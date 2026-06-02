@extends('layouts.app')
@section('title', 'Дневник')
@section('content')
<x-page-header title="Дневник" subtitle="Отчёты и события дня">
    <x-slot:actions><a href="{{ route('journal.create') }}" class="btn btn-primary">+ Запись за день</a></x-slot:actions>
</x-page-header>
<div class="space-y-4">
    @forelse($entries as $entry)
        <div class="card-hover list-row">
            <a href="{{ route('journal.show', $entry) }}" class="flex-1 min-w-0 block">
                <div class="flex justify-between items-center gap-4">
                    <span class="font-semibold text-gray-900">{{ $entry->entry_date->format('d.m.Y') }}</span>
                    @if($entry->mood)<span class="badge-gray">{{ $entry->mood }}</span>@endif
                </div>
                @if($entry->title)<h3 class="mt-2 font-medium text-gray-800">{{ $entry->title }}</h3>@endif
                <p class="text-gray-500 text-sm mt-2 line-clamp-3">{{ Str::limit($entry->content, 200) }}</p>
            </a>
            @include('partials.item-actions', [
                'edit' => route('journal.edit', $entry),
                'destroy' => route('journal.destroy', $entry),
            ])
        </div>
    @empty
        <div class="card text-center py-12 text-gray-500">Начните с отчёта за сегодня</div>
    @endforelse
</div>
<div class="mt-6">{{ $entries->links() }}</div>
@endsection
