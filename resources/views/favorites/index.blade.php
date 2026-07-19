@extends('layouts.app')
@section('title', 'Избранное')

@section('content')
<x-page-header title="Избранное" subtitle="Сохранённые мысли и цитаты со ссылками на источники" />

<x-collapsible title="Мои мысли" :count="$thoughts->count()">
    @forelse($thoughts as $thought)
        @include('books.thoughts.card', ['thought' => $thought, 'showSource' => true])
    @empty
        <div class="card text-center py-8 text-gray-500">
            Добавьте мысль в избранное кнопкой со звездой.
        </div>
    @endforelse
</x-collapsible>

<x-collapsible title="Цитаты" :count="$quotes->count()">
    @forelse($quotes as $quote)
        @include('quotes.card', ['quote' => $quote, 'showSource' => true])
    @empty
        <div class="card text-center py-10 text-gray-500">
            Добавьте цитату в избранное кнопкой со звездой.
        </div>
    @endforelse
</x-collapsible>
@endsection
