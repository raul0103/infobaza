@extends('layouts.app')
@section('title', 'Цитаты')
@section('content')
<x-page-header title="Все цитаты">
    <x-slot:actions><a href="{{ route('quotes.create') }}" class="btn btn-primary">+ Цитата</a></x-slot:actions>
</x-page-header>
<div class="space-y-4">
    @forelse($quotes as $quote)
        @include('quotes.card', ['quote' => $quote, 'showSource' => true])
    @empty
        <div class="card text-center py-12 text-gray-500">Цитат пока нет</div>
    @endforelse
</div>
<div class="mt-6">{{ $quotes->links() }}</div>
@endsection
