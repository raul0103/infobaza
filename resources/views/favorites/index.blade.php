@extends('layouts.app')
@section('title', 'Избранное')

@section('content')
@php
    $favTab = $thoughts->isNotEmpty() || ($quotes->isEmpty() && $phrases->isEmpty())
        ? 'fav-thoughts'
        : ($quotes->isNotEmpty() ? 'fav-quotes' : 'fav-phrases');
@endphp
<x-page-header title="Избранное" subtitle="Сохранённые мысли, цитаты и обороты со ссылками на источники" />

<x-tabs
    :items="[
        ['id' => 'fav-thoughts', 'label' => 'Мысли', 'count' => $thoughts->count()],
        ['id' => 'fav-quotes', 'label' => 'Цитаты', 'count' => $quotes->count()],
        ['id' => 'fav-phrases', 'label' => 'Обороты', 'count' => $phrases->count()],
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

    <x-tab-panel id="fav-phrases" :show="$favTab === 'fav-phrases'">
        <div class="space-y-2">
            @forelse($phrases as $phrase)
                @include('phrases.card', ['phrase' => $phrase, 'showSource' => true])
            @empty
                <div class="card text-center py-10 text-gray-500">
                    Добавьте оборот в избранное кнопкой со звездой.
                </div>
            @endforelse
        </div>
    </x-tab-panel>
</x-tabs>
@endsection
