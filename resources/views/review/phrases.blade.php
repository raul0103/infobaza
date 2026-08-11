@extends('layouts.app')
@section('title', 'Повторение — '.$badge)

@section('content')
<div class="max-w-3xl mx-auto w-full px-1 sm:px-0" data-csrf="{{ csrf_token() }}">
    <div class="flex items-center justify-between gap-3 mb-6">
        <a href="{{ $backUrl }}" class="link inline-flex items-center gap-1">← К выбору</a>
        @if($phrases->isNotEmpty())
            <a href="{{ $refreshUrl }}" class="btn btn-secondary text-sm shrink-0">Обновить</a>
        @endif
    </div>

    <div class="text-center mb-6">
        <span class="badge-blue">{{ $badge }}</span>
        <p class="text-sm text-gray-500 mt-2">{{ $total }} оборотов · пачка из {{ $phrases->count() }}</p>
    </div>

    @if($phrases->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="review-batch">
            @foreach($phrases as $phrase)
                <button
                    type="button"
                    class="card-hover !p-4 sm:!p-5 text-center min-h-[7.5rem] flex flex-col items-center justify-center gap-2 cursor-pointer"
                    data-kind="phrase"
                    data-text="{{ rawurlencode($phrase->text) }}"
                    data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($phrase->text)->toHtml()) }}"
                    data-context="{{ rawurlencode((string) $phrase->note) }}"
                    data-context-html="{{ rawurlencode(\App\Support\Markdown::parse($phrase->note)->toHtml()) }}"
                    data-source-label="{{ rawurlencode($phrase->sourceLabel()) }}"
                    data-answer-url="{{ route($answerRoute, $phrase) }}"
                    onclick="openReviewCard(this)"
                >
                    @if($showSourceBadge)
                        <span class="badge-blue text-[11px] max-w-full truncate">{{ $phrase->sourceLabel() }}</span>
                    @endif
                    <span class="text-base sm:text-lg font-bold text-gray-900 leading-snug break-words italic">«{{ Str::limit($phrase->text, 60) }}»</span>
                </button>
            @endforeach
        </div>
        <p class="text-center text-xs text-gray-400 mt-6">Откройте карточку — оборот отметится как повторённый</p>
    @else
        <div class="card text-center py-12">
            <p class="text-gray-500">Оборотов пока нет.</p>
            <a href="{{ $emptyUrl }}" class="btn btn-primary mt-4">{{ $emptyLabel }}</a>
        </div>
    @endif
</div>

@include('partials.card-detail-modal')
@endsection

@push('scripts')
@include('review.partials.batch-script')
@endpush
