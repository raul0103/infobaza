@extends('layouts.app')
@section('title', 'Избранное')

@section('content')
@php
    $favTab = $thoughts->isNotEmpty() || $quotes->isEmpty() ? 'fav-thoughts' : 'fav-quotes';
@endphp
<x-page-header title="Избранное" subtitle="Сохранённые мысли и цитаты со ссылками на источники" />

<x-tabs
    :items="[
        ['id' => 'fav-thoughts', 'label' => 'Мысли', 'count' => $thoughts->count()],
        ['id' => 'fav-quotes', 'label' => 'Цитаты', 'count' => $quotes->count()],
    ]"
    :active="$favTab"
>
    <x-tab-panel id="fav-thoughts" :show="$favTab === 'fav-thoughts'">
        <div class="space-y-2">
            @forelse($thoughts as $thought)
                @include('books.thoughts.card', ['thought' => $thought, 'showSource' => true])
            @empty
                <div class="card text-center py-8 text-gray-500">
                    Добавьте мысль в избранное кнопкой со звездой.
                </div>
            @endforelse
        </div>
    </x-tab-panel>

    <x-tab-panel id="fav-quotes" :show="$favTab === 'fav-quotes'">
        <div class="space-y-2">
            @forelse($quotes as $quote)
                @include('quotes.card', ['quote' => $quote, 'showSource' => true])
            @empty
                <div class="card text-center py-10 text-gray-500">
                    Добавьте цитату в избранное кнопкой со звездой.
                </div>
            @endforelse
        </div>
    </x-tab-panel>
</x-tabs>
@endsection
