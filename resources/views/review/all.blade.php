@extends('layouts.app')
@section('title', 'Повторение — все слова')

@section('content')
<div class="max-w-3xl mx-auto w-full px-1 sm:px-0" data-csrf="{{ csrf_token() }}">
    <div class="flex items-center justify-between gap-3 mb-6">
        <a href="{{ route('review.index') }}" class="link inline-flex items-center gap-1">← К выбору</a>
        @if($entries->isNotEmpty())
            <a href="{{ route('review.all') }}" class="btn btn-secondary text-sm shrink-0">Обновить</a>
        @endif
    </div>

    <div class="text-center mb-6">
        <span class="badge-blue">Все словари</span>
        <p class="text-sm text-gray-500 mt-2">{{ $total }} слов · пачка из {{ $entries->count() }}</p>
    </div>

    @if($entries->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="review-batch">
            @foreach($entries as $entry)
                <button
                    type="button"
                    class="card-hover !p-4 sm:!p-5 text-center min-h-[7.5rem] flex flex-col items-center justify-center gap-2 cursor-pointer"
                    data-term="{{ rawurlencode($entry->term) }}"
                    data-definition="{{ rawurlencode($entry->definition) }}"
                    data-definition-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->definition)->toHtml()) }}"
                    data-example="{{ rawurlencode((string) $entry->example) }}"
                    data-example-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->example)->toHtml()) }}"
                    data-dictionary-label="{{ rawurlencode($entry->dictionary->name) }}"
                    data-dictionary-url="{{ route('dictionaries.show', $entry->dictionary) }}"
                    data-answer-url="{{ route('review.all.answer', $entry) }}"
                    onclick="openReviewCard(this)"
                >
                    <span class="badge-blue text-[11px] max-w-full truncate">{{ $entry->dictionary->name }}</span>
                    <span class="text-lg sm:text-xl font-bold text-gray-900 leading-snug break-words">{{ $entry->term }}</span>
                </button>
            @endforeach
        </div>
        <p class="text-center text-xs text-gray-400 mt-6">Откройте карточку — слово отметится как повторённое</p>
    @else
        <div class="card text-center py-12">
            <p class="text-gray-500">Пока нет слов для повторения.</p>
            <a href="{{ route('dictionaries.index') }}" class="btn btn-primary mt-4">К словарям</a>
        </div>
    @endif
</div>

@include('partials.entry-detail-modal')
@endsection

@push('scripts')
@include('review.partials.batch-script')
@endpush
