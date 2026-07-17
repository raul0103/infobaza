@props(['item', 'topicGroups', 'dictionaries'])

@php
    $defaultTitle = Str::limit($item->content, 60);
    $lines = preg_split('/\r\n|\r|\n/', trim($item->content), 2);
    $defaultTerm = Str::limit($lines[0] ?? $item->content, 80);
    $defaultDefinition = trim($lines[1] ?? $item->content);
@endphp

<form method="POST" action="{{ route('inbox.convert', $item) }}" class="space-y-3 border-t border-gray-100 pt-4 inbox-convert-form">@csrf
    <div class="form-group">
        <label class="label">Куда сохранить</label>
        <select name="target" class="select inbox-target" required>
            <option value="note">Запись</option>
            <option value="book">Книга → на очереди</option>
            <option value="movie">Фильм → на очереди</option>
            <option value="word">Слово в словарь</option>
        </select>
    </div>

    <div class="inbox-panel inbox-panel-note space-y-3">
        <div class="form-group">
            <label class="label">Заголовок</label>
            <input name="title" class="input" value="{{ $defaultTitle }}" required>
        </div>
        <div class="form-group">
            <label class="label">Тема</label>
            <select name="topic_id" class="select">
                <option value="">—</option>
                @include('partials.topic-select-options', ['groups' => $topicGroups, 'selected' => null])
            </select>
        </div>
    </div>

    <div class="inbox-panel inbox-panel-book hidden space-y-3">
        <div class="form-group">
            <label class="label">Название книги</label>
            <input name="title" class="input" value="{{ $defaultTitle }}" disabled>
        </div>
        <div class="form-group">
            <label class="label">Автор</label>
            <input name="author" class="input" placeholder="Необязательно">
        </div>
        <p class="hint">Попадёт в раздел «Хочу прочитать». Заметка из инбокса — в описание книги.</p>
    </div>

    <div class="inbox-panel inbox-panel-movie hidden space-y-3">
        <div class="form-group">
            <label class="label">Название фильма</label>
            <input name="title" class="input" value="{{ $defaultTitle }}" disabled>
        </div>
        <div class="form-group">
            <label class="label">Режиссёр</label>
            <input name="director" class="input" placeholder="Необязательно">
        </div>
        <p class="hint">Попадёт в раздел «Хочу посмотреть». Текст инбокса — в заметки о фильме.</p>
    </div>

    <div class="inbox-panel inbox-panel-word hidden space-y-3">
        <div class="form-group">
            <label class="label">Словарь</label>
            <select name="dictionary_id" class="select" disabled>
                <option value="">Выберите словарь</option>
                @foreach($dictionaries as $dict)
                    <option value="{{ $dict->id }}">{{ $dict->name }}</option>
                @endforeach
            </select>
            @if($dictionaries->isEmpty())
                <p class="hint mt-1"><a href="{{ route('dictionaries.create') }}" class="link">Создайте словарь</a>, чтобы добавлять слова.</p>
            @endif
        </div>
        <div class="form-group">
            <label class="label">Слово</label>
            <input name="term" class="input" value="{{ $defaultTerm }}" disabled>
        </div>
        <div class="form-group">
            <label class="label">Значение</label>
            <textarea name="definition" class="textarea" rows="3" disabled>{{ $defaultDefinition }}</textarea>
        </div>
        <div class="form-group">
            <label class="label">Пример</label>
            <input name="example" class="input" placeholder="Необязательно" disabled>
        </div>
        <p class="hint">Первая строка — слово, остальное — значение (можно поправить).</p>
    </div>

    <button type="submit" class="btn btn-primary w-full sm:w-auto inbox-submit">Сохранить</button>
</form>

@once
@push('scripts')
<script>
document.querySelectorAll('.inbox-convert-form').forEach(form => {
    const target = form.querySelector('.inbox-target');
    const panels = {
        note: form.querySelector('.inbox-panel-note'),
        book: form.querySelector('.inbox-panel-book'),
        movie: form.querySelector('.inbox-panel-movie'),
        word: form.querySelector('.inbox-panel-word'),
    };
    const submit = form.querySelector('.inbox-submit');
    const labels = { note: '→ Создать запись', book: '→ В книги', movie: '→ В фильмы', word: '→ В словарь' };

    const sync = () => {
        const t = target.value;
        Object.entries(panels).forEach(([key, panel]) => {
            const active = key === t;
            panel.classList.toggle('hidden', !active);
            panel.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.name === 'target') return;
                el.disabled = !active;
                if (active && el.name === 'title' && key !== 'note') {
                    el.disabled = false;
                    el.required = true;
                } else if (active && el.name === 'title' && key === 'note') {
                    el.required = true;
                } else if (!active) {
                    el.required = false;
                }
            });
        });
        if (t === 'word') {
            const dict = panels.word.querySelector('[name="dictionary_id"]');
            const term = panels.word.querySelector('[name="term"]');
            const def = panels.word.querySelector('[name="definition"]');
            if (dict) { dict.disabled = false; dict.required = true; }
            if (term) { term.disabled = false; term.required = true; }
            if (def) { def.disabled = false; def.required = true; }
            panels.word.querySelector('[name="example"]').disabled = false;
        }
        submit.textContent = labels[t] || 'Сохранить';
    };

    target.addEventListener('change', sync);
    sync();
});
</script>
@endpush
@endonce
