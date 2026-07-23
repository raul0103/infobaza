@extends('layouts.app')
@section('title', 'Планы')
@section('content')
<x-page-header title="Планы" subtitle="Что хотите сделать">
    <x-slot:actions>
        <a href="{{ route('plans.create') }}" class="btn btn-primary">+ План</a>
    </x-slot:actions>
</x-page-header>

@if($hasPlans)
    @if($queuedPlans->isNotEmpty())
        <x-collapsible title="Хочу сделать" :count="$queuedPlans->count()" :open="true">
            <x-slot:actions>
                <a href="{{ route('plans.create', ['status' => 'queued']) }}" class="link">+ Добавить</a>
            </x-slot:actions>

            <div class="divide-y divide-gray-100">
                @foreach($queuedPlans as $plan)
                    @include('plans.partials.card', ['plan' => $plan])
                @endforeach
            </div>
        </x-collapsible>
    @endif

    @php $otherSections = $sections->filter(fn ($s) => $s['count'] > 0); @endphp
    @if($otherSections->isNotEmpty())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 {{ $queuedPlans->isNotEmpty() ? 'mt-6' : '' }}">
            @foreach($otherSections as $section)
                <x-category-card
                    :href="route('plans.status', $section['status'])"
                    :title="$section['label']"
                    :subtitle="$section['count'].' планов'"
                />
            @endforeach
        </div>
    @endif
@else
    <div class="card text-center py-12 text-gray-500">
        <p class="mb-3">Создайте первый план</p>
        <a href="{{ route('plans.create') }}" class="btn btn-primary">Создать план</a>
    </div>
@endif
@endsection
