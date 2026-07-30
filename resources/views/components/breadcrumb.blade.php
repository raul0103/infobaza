@props(['items' => []])

{{-- items: [['label' => '…', 'url' => optional], ...] — last item is current page --}}
<ol class="flex flex-wrap items-center gap-x-1.5 gap-y-1 text-sm text-gray-500">
    @foreach($items as $item)
        <li class="inline-flex items-center gap-1.5 min-w-0">
            @if(! $loop->first)
                <svg class="w-3.5 h-3.5 shrink-0 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @endif
            @if(! empty($item['url']) && ! $loop->last)
                <a href="{{ $item['url'] }}" class="truncate font-medium text-gray-500 hover:text-blue-700 transition-colors">{{ $item['label'] }}</a>
            @else
                <span @class(['truncate', 'font-medium text-gray-800' => $loop->last])>{{ $item['label'] }}</span>
            @endif
        </li>
    @endforeach
</ol>
