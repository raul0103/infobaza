@php
    $sections = [
        [
            'label' => null,
            'items' => [
                ['route' => 'dashboard', 'label' => 'Главная', 'icon' => 'home'],
                ['route' => 'dictionaries.*', 'match' => ['dictionaries.*'], 'label' => 'Словари', 'icon' => 'dictionary'],
                ['route' => 'books.*', 'label' => 'Книги', 'icon' => 'book'],
                ['route' => 'movies.*', 'match' => ['movies.*'], 'label' => 'Фильмы', 'icon' => 'film'],
                ['route' => 'favorites.*', 'label' => 'Избранное', 'icon' => 'star'],
                ['route' => 'memos.*', 'label' => 'Заметки', 'icon' => 'memo'],
                ['route' => 'plans.*', 'match' => ['plans.*'], 'label' => 'Планы', 'icon' => 'plan'],
            ],
        ],
        [
            'label' => 'База знаний',
            'items' => [
                ['route' => 'topics.*', 'label' => 'Темы', 'icon' => 'folder'],
                ['route' => 'notes.*', 'label' => 'Записи', 'icon' => 'document'],
                ['route' => 'facts.*', 'match' => ['facts.*', 'fact-groups.*', 'review.facts', 'review.facts.*'], 'label' => 'Факты', 'icon' => 'fact'],
                ['route' => 'jokes.*', 'label' => 'Анекдоты', 'icon' => 'joke'],
            ],
        ],
        [
            'label' => 'Обучение',
            'items' => [
                ['href' => route('review.index'), 'match' => ['review.index', 'review.all', 'review.all.answer', 'review.session', 'review.answer'], 'label' => 'Повторение', 'icon' => 'repeat'],
                ['route' => 'exam', 'match' => 'exam*', 'label' => 'Экзамен', 'icon' => 'exam'],
            ],
        ],
        [
            'label' => 'Справка',
            'items' => [
                ['route' => 'guide.*', 'label' => 'Руководство', 'icon' => 'help'],
            ],
        ],
    ];
@endphp

@foreach($sections as $section)
    @if($section['label'])
        <p class="nav-section-label">{{ $section['label'] }}</p>
    @endif
    <div class="space-y-0.5 mb-1">
        @foreach($section['items'] as $item)
            @php
                $href = $item['href'] ?? (
                    $item['route'] === 'dashboard'
                        ? route('dashboard')
                        : route(str_replace('.*', '.index', $item['route']))
                );
                $active = request()->routeIs(...(array) ($item['match'] ?? $item['route'] ?? []));
            @endphp
            <a href="{{ $href }}" class="nav-link {{ $active ? 'active' : '' }}">
                @include('partials.nav-icon', ['name' => $item['icon']])
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
    @if(! $loop->last)
        <div class="my-3 border-t border-gray-100" role="separator"></div>
    @endif
@endforeach
