@extends('layouts.app')
@section('title', 'Книги')
@section('content')
<x-page-header title="Книги" subtitle="Библиотека и цитаты из книг">
    <x-slot:actions><a href="{{ route('books.create') }}" class="btn btn-primary">+ Книга</a></x-slot:actions>
</x-page-header>

@php $hasBooks = $sections->sum(fn ($s) => $s['books']->count()) > 0; @endphp

@foreach($sections as $section)
    @if($section['books']->isNotEmpty())
        <h2 class="section-title mb-3 mt-6 first:mt-0">{{ $section['label'] }}</h2>
        <div class="space-y-3 mb-2">
            @foreach($section['books'] as $book)
                <div class="card-hover list-row sm:items-center">
                    <a href="{{ route('books.show', $book) }}" class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900">{{ $book->title }}</h3>
                        @if($book->author)<p class="text-sm text-gray-500 mt-0.5">{{ $book->author }}@if($book->year), {{ $book->year }}@endif</p>@endif
                        @if($book->status === 'reading' && $book->total_pages)
                            <div class="mt-2 max-w-xs"><div class="h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-emerald-500 rounded-full" style="width:{{ $book->readingPercent() }}%"></div></div></div>
                        @endif
                    </a>
                    <div class="flex items-center gap-4 shrink-0">
                        <span class="badge-gray hidden sm:inline">{{ $book->quotes_count }} цитат</span>
                        @include('partials.item-actions', [
                            'edit' => route('books.edit', $book),
                            'destroy' => route('books.destroy', $book),
                        ])
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endforeach

@if(! $hasBooks)
    <div class="card text-center py-12"><p class="text-gray-500">Добавьте первую книгу или перенесите из инбокса</p></div>
@endif
@endsection
