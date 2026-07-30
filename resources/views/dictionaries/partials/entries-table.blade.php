{{-- Expects: $dictionary, $entries; optional: $showGroupBadge, $q, $emptyEditUrl --}}
@php
    $letterGroups = $entries->groupBy(function ($entry) {
        $term = ltrim((string) $entry->term);
        if ($term === '') {
            return '#';
        }

        $letter = mb_strtoupper(mb_substr($term, 0, 1));

        return preg_match('/^\p{L}$/u', $letter) ? $letter : '#';
    });
@endphp
<div class="card overflow-hidden p-0">
    <div class="table-scroll">
    <table class="w-full min-w-[32rem] text-left text-sm">
        <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
            <tr>
                <th class="px-4 sm:px-6 py-3 font-medium">Слово</th>
                <th class="px-4 sm:px-6 py-3 font-medium">Значение</th>
                <th class="px-4 sm:px-6 py-3 w-28 sm:w-32"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($letterGroups as $letter => $groupEntries)
                <tr class="bg-gray-50/90">
                    <td colspan="3" class="px-4 sm:px-6 py-1.5 border-y border-gray-200">
                        <span class="text-xs font-semibold tracking-widest text-gray-500">{{ $letter }}</span>
                    </td>
                </tr>
                @foreach($groupEntries as $entry)
                    <tr class="hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0"
                        onclick="if (!event.target.closest('a, button, form')) openEntryModal(this)"
                        data-term="{{ rawurlencode($entry->term) }}"
                        data-definition="{{ rawurlencode($entry->definition) }}"
                        data-definition-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->definition)->toHtml()) }}"
                        data-example="{{ rawurlencode((string) $entry->example) }}"
                        data-example-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->example)->toHtml()) }}"
                        data-edit-url="{{ route('dictionaries.entries.edit', [$dictionary, $entry]) }}">
                        <td class="px-4 sm:px-6 py-3 font-medium text-gray-900 align-top">
                            <div>{{ $entry->term }}</div>
                            @if(($showGroupBadge ?? false) && $entry->group)
                                <a href="#group-{{ $entry->group_id }}" class="badge-gray mt-1 inline-flex hover:bg-blue-50 hover:text-blue-700"
                                   onclick="event.preventDefault(); activateTab(this.closest('[data-tabs]'), 'group-{{ $entry->group_id }}')">
                                    {{ $entry->group->displayTitle() }}
                                </a>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 text-gray-600 align-top">
                            {{ Str::limit($entry->definition, 80) }}
                        </td>
                        <td class="px-4 sm:px-6 py-3 text-left sm:text-right align-top whitespace-nowrap">
                            @include('partials.item-actions', [
                                'edit' => route('dictionaries.entries.edit', [$dictionary, $entry]),
                                'destroy' => route('dictionaries.entries.destroy', [$dictionary, $entry]),
                            ])
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                        @if(($q ?? '') !== '')
                            Ничего не найдено по «{{ $q }}»
                        @elseif(! empty($emptyEditUrl))
                            <p class="mb-3">В объединении пока нет слов</p>
                            <a href="{{ $emptyEditUrl }}" class="btn btn-primary">Добавить слова</a>
                        @else
                            <p class="mb-3">Добавьте слова для изучения</p>
                            <a href="{{ route('dictionaries.entries.create', $dictionary) }}" class="btn btn-primary">+ Слово</a>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
