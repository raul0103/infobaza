@extends('layouts.app')
@section('title', 'Планы')
@section('content')
<x-page-header title="Планы" subtitle="Что хотите сделать">
    <x-slot:actions>
        <a href="{{ route('plans.create') }}" class="btn btn-primary">+ План</a>
    </x-slot:actions>
</x-page-header>

@php
    $visible = $sections->filter(fn ($s) => $s['plans']->isNotEmpty())->values();
    $planTabs = $visible->map(fn ($s) => [
        'id' => 'plans-'.$s['status'],
        'label' => $s['label'],
        'count' => $s['plans']->count(),
    ])->all();
    $openStatus = $visible->first(fn ($s) => $s['status'] === 'queued')['status']
        ?? ($visible->first()['status'] ?? null);
    $planActive = $openStatus ? 'plans-'.$openStatus : null;
@endphp

@if($visible->isNotEmpty())
    <x-tabs :items="$planTabs" :active="$planActive">
        @foreach($visible as $section)
            @php $sid = 'plans-'.$section['status']; @endphp
            <x-tab-panel :id="$sid" :show="$planActive === $sid">
                <x-slot:actions>
                    <a href="{{ route('plans.create', ['status' => $section['status']]) }}" class="link">+ Добавить</a>
                </x-slot:actions>

                <div class="divide-y divide-gray-100">
                    @foreach($section['plans'] as $plan)
                        @include('plans.partials.card', ['plan' => $plan])
                    @endforeach
                </div>
            </x-tab-panel>
        @endforeach
    </x-tabs>
@else
    <div class="card text-center py-12 text-gray-500">
        <p class="mb-3">Создайте первый план</p>
        <a href="{{ route('plans.create') }}" class="btn btn-primary">Создать план</a>
    </div>
@endif
@endsection
