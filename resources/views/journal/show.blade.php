@extends('layouts.app')
@section('title', $entry->entry_date->format('d.m.Y'))
@section('content')
<x-page-header :title="$entry->entry_date->translatedFormat('d F Y')" :subtitle="$entry->title">
    <x-slot:actions>
        @if($entry->mood)<span class="badge-gray">{{ $entry->mood }}</span>@endif
        @include('partials.item-actions', [
            'edit' => route('journal.edit', $entry),
            'destroy' => route('journal.destroy', $entry),
        ])
    </x-slot:actions>
</x-page-header>
<div class="card">
    <div class="whitespace-pre-wrap text-gray-700 leading-relaxed text-base">{{ $entry->content }}</div>
</div>
@endsection
