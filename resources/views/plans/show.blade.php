@extends('layouts.app')
@section('title', $plan->title)

@section('content')
@php
    $total = $plan->steps->count();
    $done = $plan->steps->where('is_done', true)->count();
    $percent = $total > 0 ? (int) round(($done / $total) * 100) : null;
@endphp

<x-page-header :title="$plan->title" :subtitle="\App\Models\Plan::statusLabels()[$plan->status] ?? ''">
    <x-slot:actions>
        <a href="{{ route('plans.status', $plan->status) }}" class="btn btn-secondary">К списку</a>
        @include('partials.item-actions', [
            'edit' => route('plans.edit', $plan),
            'destroy' => route('plans.destroy', $plan),
        ])
    </x-slot:actions>
</x-page-header>

@if(filled($plan->description))
    <div class="card mb-6">
        <x-markdown :content="$plan->description" class="text-gray-700" />
    </div>
@endif

@if($total > 0)
    <div class="card mb-6 !p-3 sm:!p-4">
        <div class="flex justify-between text-xs sm:text-sm mb-2">
            <span class="text-gray-600">Прогресс</span>
            <span class="font-medium tabular-nums">{{ $done }} / {{ $total }} · {{ $percent }}%</span>
        </div>
        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $percent }}%"></div>
        </div>
    </div>
@endif

<div class="card mb-6">
    <div class="flex items-center justify-between gap-3 mb-4">
        <h2 class="section-title">Шаги</h2>
        <span class="badge-gray">{{ $total }}</span>
    </div>

    <div class="space-y-2 mb-4">
        @forelse($plan->steps as $step)
            <div class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2.5 {{ $step->is_done ? 'bg-emerald-50/50 border-emerald-100' : 'bg-white' }}">
                <form method="POST" action="{{ route('plans.steps.toggle', [$plan, $step]) }}" class="shrink-0">
                    @csrf
                    @method('PATCH')
                    <button
                        type="submit"
                        class="flex h-6 w-6 items-center justify-center rounded-md border {{ $step->is_done ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-gray-300 text-transparent hover:border-emerald-400' }}"
                        title="{{ $step->is_done ? 'Отметить невыполненным' : 'Отметить выполненным' }}"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </form>
                <span @class(['flex-1 min-w-0 text-sm', 'text-gray-400 line-through' => $step->is_done, 'text-gray-800' => ! $step->is_done])>
                    {{ $step->title }}
                </span>
                @include('partials.delete-form', [
                    'action' => route('plans.steps.destroy', [$plan, $step]),
                    'compact' => true,
                ])
            </div>
        @empty
            <p class="text-sm text-gray-500 py-2">Добавьте шаги</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('plans.steps.store', $plan) }}" class="flex gap-2">
        @csrf
        <input
            type="text"
            name="title"
            class="input flex-1"
            placeholder="Новый шаг…"
            required
            maxlength="255"
            autofocus
        >
        <button type="submit" class="btn btn-primary shrink-0">Добавить</button>
    </form>
    @error('title')
        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
@endsection
