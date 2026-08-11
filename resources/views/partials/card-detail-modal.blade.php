@once
@push('modals')
<x-modal id="card-detail-modal" title="Запись" size="lg">
    <div id="card-modal-text" class="markdown-body text-gray-800"></div>

    <div id="card-modal-context-wrap" class="hidden mt-4 rounded-lg bg-blue-50/60 px-3 py-2 text-sm text-gray-600">
        <span class="text-xs font-medium uppercase tracking-wide text-blue-500">Контекст</span>
        <div id="card-modal-context" class="mt-1 markdown-body"></div>
    </div>

    <div id="card-modal-meta" class="hidden flex flex-wrap gap-2 mt-4"></div>

    <div id="card-modal-source-wrap" class="hidden mt-4 border-t border-gray-100 pt-4">
        <a id="card-modal-source" href="#" class="link"></a>
        <p id="card-modal-source-text" class="hidden text-sm text-gray-500"></p>
    </div>

    <div id="card-modal-actions" class="hidden mt-4 border-t border-gray-100 pt-4">
        <a id="card-modal-edit" href="#" class="btn btn-secondary text-sm">Редактировать</a>
    </div>
</x-modal>
@endpush

@push('scripts')
<script>
function openCardModal(el) {
    const d = el.dataset;
    const decode = (value) => decodeURIComponent(value || '');
    const kind = d.kind || 'thought';
    const text = decode(d.text);
    const textHtml = decode(d.textHtml || '');
    const title = decode(d.title);

    const titles = { quote: 'Цитата', thought: 'Мысль', tip: 'Приём', fact: 'Факт', joke: 'Анекдот', phrase: 'Оборот речи' };
    document.getElementById('card-detail-modal-title').textContent =
        ((kind === 'tip' || kind === 'fact') && title) ? title : (titles[kind] || 'Запись');

    const textEl = document.getElementById('card-modal-text');
    if (kind === 'quote' || kind === 'phrase') {
        textEl.classList.add('italic');
        if (textHtml) {
            textEl.innerHTML = textHtml;
        } else {
            textEl.textContent = '«' + text + '»';
        }
    } else {
        textEl.classList.remove('italic');
        if (textHtml) {
            textEl.innerHTML = textHtml;
        } else {
            textEl.textContent = text;
        }
    }

    const contextWrap = document.getElementById('card-modal-context-wrap');
    const context = decode(d.context);
    const contextHtml = decode(d.contextHtml || '');
    contextWrap.classList.toggle('hidden', !(context || contextHtml));
    const contextLabel = contextWrap.querySelector('span');
    if (contextLabel) {
        contextLabel.textContent = kind === 'phrase' ? 'Пояснение' : 'Контекст';
    }
    const contextEl = document.getElementById('card-modal-context');
    if (contextHtml) {
        contextEl.innerHTML = contextHtml;
    } else {
        contextEl.textContent = context;
    }

    const meta = document.getElementById('card-modal-meta');
    meta.innerHTML = '';
    const badges = [];
    if (decode(d.chapter)) badges.push(decode(d.chapter));
    if (decode(d.page)) badges.push(kind === 'tip' ? decode(d.page) : 'Стр. ' + decode(d.page));
    if (decode(d.character)) badges.push('— ' + decode(d.character));
    badges.forEach((label) => {
        const badge = document.createElement('span');
        badge.className = 'badge-gray';
        badge.textContent = label;
        meta.appendChild(badge);
    });
    meta.classList.toggle('hidden', badges.length === 0);

    const sourceWrap = document.getElementById('card-modal-source-wrap');
    const sourceLink = document.getElementById('card-modal-source');
    const sourceText = document.getElementById('card-modal-source-text');
    const sourceLabel = decode(d.sourceLabel);
    if (sourceLabel && d.sourceUrl) {
        sourceLink.textContent = 'Источник: ' + sourceLabel + ' →';
        sourceLink.href = d.sourceUrl;
        sourceLink.classList.remove('hidden');
        sourceText.classList.add('hidden');
        sourceWrap.classList.remove('hidden');
    } else if (sourceLabel) {
        sourceText.textContent = 'Источник: ' + sourceLabel;
        sourceText.classList.remove('hidden');
        sourceLink.classList.add('hidden');
        sourceWrap.classList.remove('hidden');
    } else {
        sourceWrap.classList.add('hidden');
    }

    const actionsWrap = document.getElementById('card-modal-actions');
    const editLink = document.getElementById('card-modal-edit');
    if (d.editUrl) {
        editLink.href = d.editUrl;
        actionsWrap.classList.remove('hidden');
    } else {
        actionsWrap.classList.add('hidden');
    }

    openModal('card-detail-modal');
}
</script>
@endpush
@endonce
