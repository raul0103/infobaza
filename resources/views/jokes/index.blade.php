@extends('layouts.app')
@section('title', 'Анекдоты')

@section('content')
<x-page-header title="Анекдоты" subtitle="Избранные анекдоты">
    <x-slot:actions>
        <a href="{{ route('jokes.create') }}" class="btn btn-primary">+ Анекдот</a>
    </x-slot:actions>
</x-page-header>

<div class="space-y-4">
    @forelse($jokes as $joke)
        <div class="card p-4 sm:p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800 leading-relaxed"><x-markdown :content="$joke->text" /></p>
                    @if($joke->source)
                        <p class="text-xs text-blue-600 font-medium mt-2">{{ $joke->source }}</p>
                    @endif
                </div>
                @include('partials.item-actions', [
                    'edit' => route('jokes.edit', $joke),
                    'destroy' => route('jokes.destroy', $joke),
                ])
            </div>
        </div>
    @empty
        <div class="card text-center py-12 text-gray-500">Пока нет сохранённых анекдотов</div>
    @endforelse
</div>

<div class="mt-6">{{ $jokes->links() }}</div>
@endsection
