@extends('layouts.app')
@section('title', 'Избранное')

@section('content')
<x-page-header title="Избранное" subtitle="Сохранённые мысли и цитаты со ссылками на источники" />

<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <h2 class="section-title">Мои мысли <span class="text-gray-400 font-normal">({{ $thoughts->count() }})</span></h2>
</div>
<div class="space-y-4">
    @forelse($thoughts as $thought)
        @include('books.thoughts.card', ['thought' => $thought, 'showSource' => true])
    @empty
        <div class="card text-center py-8 text-gray-500">
            Добавьте мысль в избранное кнопкой со звездой.
        </div>
    @endforelse
</div>

<div class="flex flex-wrap items-center justify-between gap-3 mt-8 mb-4">
    <h2 class="section-title">Цитаты <span class="text-gray-400 font-normal">({{ $quotes->count() }})</span></h2>
</div>
<div class="space-y-4">
    @forelse($quotes as $quote)
        @include('quotes.card', ['quote' => $quote, 'showSource' => true])
    @empty
        <div class="card text-center py-10 text-gray-500">
            Добавьте цитату в избранное кнопкой со звездой.
        </div>
    @endforelse
</div>
@endsection
