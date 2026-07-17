@extends('layouts.app')
@section('title', 'Интересные факты')

@section('content')
<x-page-header title="Интересные факты" subtitle="Короткие заметки и любопытные сведения">
    <x-slot:actions>
        @if($facts->total() > 0)
            <a href="{{ route('review.facts') }}" class="btn btn-secondary">Повторять</a>
        @endif
        <a href="{{ route('facts.create') }}" class="btn btn-primary">+ Факт</a>
    </x-slot:actions>
</x-page-header>

<div class="space-y-4">
    @forelse($facts as $fact)
        <div class="card p-4 sm:p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    @if($fact->title)
                        <h3 class="font-semibold text-gray-900 mb-1.5">{{ $fact->title }}</h3>
                    @endif
                    <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $fact->text }}</p>
                    @if($fact->source)
                        <p class="text-xs text-blue-600 font-medium mt-2">{{ $fact->source }}</p>
                    @endif
                </div>
                @include('partials.item-actions', [
                    'edit' => route('facts.edit', $fact),
                    'destroy' => route('facts.destroy', $fact),
                ])
            </div>
        </div>
    @empty
        <div class="card text-center py-12 text-gray-500">Пока нет интересных фактов</div>
    @endforelse
</div>

<div class="mt-6">{{ $facts->links() }}</div>
@endsection
