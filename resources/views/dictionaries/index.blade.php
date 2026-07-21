@extends('layouts.app')
@section('title', 'Словари')
@section('content')
<x-page-header title="Словари" subtitle="Слова и фразы для изучения">
    <x-slot:actions><a href="{{ route('dictionaries.create') }}" class="btn btn-primary">+ Словарь</a></x-slot:actions>
</x-page-header>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
    @forelse($dictionaries as $dict)
        <div class="card-hover !p-3 flex items-center gap-2">
            <a href="{{ route('dictionaries.show', $dict) }}" class="min-w-0 flex-1">
                <div class="font-medium text-gray-900 hover:text-blue-600 truncate">{{ $dict->name }}</div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $dict->entries_count }} слов</div>
            </a>
            <a href="{{ route('review.session', $dict) }}" class="btn btn-success text-xs !px-2.5 !py-1.5 shrink-0" title="Повторить">▶</a>
            @include('partials.item-actions', [
                'edit' => route('dictionaries.edit', $dict),
                'destroy' => route('dictionaries.destroy', $dict),
            ])
        </div>
    @empty
        <div class="col-span-full card text-center py-12 text-gray-500">Создайте словарь для изучения терминов</div>
    @endforelse
</div>
@endsection
