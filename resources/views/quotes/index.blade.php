@extends('layouts.app')
@section('title', 'Цитаты')
@section('content')
<x-page-header title="Все цитаты">
    <x-slot:actions><a href="{{ route('quotes.create') }}" class="btn btn-primary">+ Цитата</a></x-slot:actions>
</x-page-header>
<div class="space-y-4">
    @forelse($quotes as $quote)
        <div class="card">
            <blockquote class="text-lg italic text-gray-800 leading-relaxed">«{{ $quote->text }}»</blockquote>
            <p class="text-sm text-blue-600 font-medium mt-3">{{ $quote->sourceLabel() }}</p>
            <div class="mt-4 pt-3 border-t border-gray-100">
                @include('partials.item-actions', [
                    'edit' => route('quotes.edit', $quote),
                    'destroy' => route('quotes.destroy', $quote),
                ])
            </div>
        </div>
    @empty
        <div class="card text-center py-12 text-gray-500">Цитат пока нет</div>
    @endforelse
</div>
<div class="mt-6">{{ $quotes->links() }}</div>
@endsection
