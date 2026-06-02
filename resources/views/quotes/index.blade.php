@extends('layouts.app')
@section('title', 'Цитаты')
@section('content')
<x-page-header title="Все цитаты">
    <x-slot:actions><a href="{{ route('quotes.create') }}" class="btn btn-primary">+ Цитата</a></x-slot:actions>
</x-page-header>
<div class="space-y-4">
    @forelse($quotes as $quote)
        <div class="card p-4 sm:p-4">
            <div class="flex items-start justify-between gap-3">
                <blockquote class="text-sm italic text-gray-800 leading-relaxed flex-1">«{{ $quote->text }}»</blockquote>
                @include('partials.item-actions', [
                    'edit' => route('quotes.edit', $quote),
                    'destroy' => route('quotes.destroy', $quote),
                ])
            </div>
            <p class="text-xs text-blue-600 font-medium mt-2">{{ $quote->sourceLabel() }}</p>
        </div>
    @empty
        <div class="card text-center py-12 text-gray-500">Цитат пока нет</div>
    @endforelse
</div>
<div class="mt-6">{{ $quotes->links() }}</div>
@endsection
