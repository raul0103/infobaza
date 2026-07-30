@extends('layouts.app')
@section('title', $label)

@section('content')
<x-page-header :title="$label">
    <x-slot:breadcrumb>
        <x-breadcrumb :items="[
            ['label' => 'Планы', 'url' => route('plans.index')],
            ['label' => $label],
        ]" />
    </x-slot:breadcrumb>
    <x-slot:actions>
        <a href="{{ route('plans.create', ['status' => $status]) }}" class="btn btn-primary">+ План</a>
    </x-slot:actions>
</x-page-header>

<div class="divide-y divide-gray-100">
    @forelse($plans as $plan)
        @include('plans.partials.card', ['plan' => $plan])
    @empty
        <div class="card text-center py-12 text-gray-500">В этом статусе пока нет планов</div>
    @endforelse
</div>
@endsection
