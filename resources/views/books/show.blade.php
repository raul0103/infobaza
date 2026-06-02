@extends('layouts.app')
@section('title', $book->title)
@section('content')
<x-page-header :title="$book->title" :subtitle="trim(($book->author ?? '').($book->year ? ', '.$book->year : ''))">
    <x-slot:actions>
        <span class="badge-gray">{{ \App\Models\Book::statusLabels()[$book->status] ?? '' }}</span>
        <a href="{{ route('quotes.create', ['book_id' => $book->id]) }}" class="btn btn-primary">+ Цитата</a>
        @include('partials.item-actions', [
            'edit' => route('books.edit', $book),
            'destroy' => route('books.destroy', $book),
        ])
    </x-slot:actions>
</x-page-header>
@php
    $readingPercent = $book->readingPercent() ?? 0;
@endphp
@if($book->status === 'reading' && $book->total_pages)
<div class="card mb-6">
    <div class="flex justify-between text-sm mb-2"><span class="text-gray-600">Прогресс чтения</span><span class="font-medium">{{ $book->current_page ?? 0 }} / {{ $book->total_pages }} ({{ $readingPercent }}%)</span></div>
    <progress class="w-full h-3 rounded-full overflow-hidden [&::-webkit-progress-bar]:bg-gray-100 [&::-webkit-progress-value]:bg-emerald-500 [&::-moz-progress-bar]:bg-emerald-500" max="100" value="{{ $readingPercent }}"></progress>
</div>
@endif
@if($book->review_takeaway)
<div class="card mb-6 border-l-4 border-l-emerald-500"><h2 class="section-title mb-2">Главный вывод</h2><p class="text-gray-600 whitespace-pre-wrap">{{ $book->review_takeaway }}</p></div>
@endif
@if($book->description)<div class="card mb-6 text-gray-600">{{ $book->description }}</div>@endif
<h2 class="section-title mb-4">Цитаты <span class="text-gray-400 font-normal">({{ $book->quotes->count() }})</span></h2>
<div class="space-y-4">
    @forelse($book->quotes as $quote)
        <div class="card border-l-4 border-l-blue-500 p-3 sm:p-3">
            <blockquote class="text-sm text-gray-800 italic leading-relaxed">«{{ $quote->text }}»</blockquote>
            @if($quote->page)<p class="text-sm text-gray-500 mt-3">Стр. {{ $quote->page }}</p>@endif
            <div class="mt-3 pt-2 border-t border-gray-100">
                @include('partials.item-actions', [
                    'edit' => route('quotes.edit', $quote),
                    'destroy' => route('quotes.destroy', $quote),
                ])
            </div>
        </div>
    @empty
        <div class="card text-center py-10 text-gray-500">Выписывайте цитаты по мере чтения</div>
    @endforelse
</div>
@endsection
