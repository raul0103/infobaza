@extends('layouts.app')
@section('title', 'Словари')
@section('content')
<x-page-header title="Словари" subtitle="Слова и фразы для изучения">
    <x-slot:actions><a href="{{ route('dictionaries.create') }}" class="btn btn-primary">+ Словарь</a></x-slot:actions>
</x-page-header>
<div class="grid md:grid-cols-2 gap-4">
    @forelse($dictionaries as $dict)
        <div class="card-hover">
            <a href="{{ route('dictionaries.show', $dict) }}" class="font-semibold text-lg text-gray-900 hover:text-blue-600">{{ $dict->name }}</a>
            <p class="text-sm text-gray-500 mt-1">{{ $dict->entries_count }} слов</p>
            <div class="flex flex-wrap items-center gap-3 mt-4">
                <a href="{{ route('review.session', $dict) }}" class="btn btn-success text-sm py-2">▶ Повторить</a>
                @include('partials.item-actions', [
                    'edit' => route('dictionaries.edit', $dict),
                    'destroy' => route('dictionaries.destroy', $dict),
                ])
            </div>
        </div>
    @empty
        <div class="col-span-full card text-center py-12 text-gray-500">Создайте словарь для изучения терминов</div>
    @endforelse
</div>
@endsection
