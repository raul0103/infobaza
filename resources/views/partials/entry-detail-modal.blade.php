@once
@push('modals')
<x-modal id="entry-detail-modal" title="Слово" size="lg">
    <div id="entry-modal-definition" class="whitespace-pre-wrap text-gray-800 mb-4"></div>
    <div id="entry-modal-example-wrap" class="hidden border-t border-gray-100 pt-4">
        <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Пример</p>
        <p id="entry-modal-example" class="text-gray-600 italic whitespace-pre-wrap"></p>
    </div>
    <div id="entry-modal-source-wrap" class="hidden mt-4 border-t border-gray-100 pt-4">
        <a id="entry-modal-source" href="#" class="link"></a>
    </div>
    <div id="entry-modal-actions" class="hidden mt-4 border-t border-gray-100 pt-4">
        <a id="entry-modal-edit" href="#" class="btn btn-secondary text-sm">Редактировать</a>
    </div>
</x-modal>
@endpush

@push('scripts')
<script>
function openEntryModal(termOrEl, definition, example, dictionaryLabel, dictionaryUrl) {
    let term = termOrEl;
    let def = definition || '';
    let ex = example || '';
    let dictLabel = dictionaryLabel || '';
    let dictUrl = dictionaryUrl || '';
    let editUrl = '';

    if (termOrEl && typeof termOrEl === 'object' && termOrEl.dataset) {
        const d = termOrEl.dataset;
        const decode = (v) => decodeURIComponent(v || '');
        term = decode(d.term);
        def = decode(d.definition);
        ex = decode(d.example);
        dictLabel = decode(d.dictionaryLabel);
        dictUrl = d.dictionaryUrl || '';
        editUrl = d.editUrl || '';
    }

    document.getElementById('entry-detail-modal-title').textContent = term;
    document.getElementById('entry-modal-definition').textContent = def;

    const exampleWrap = document.getElementById('entry-modal-example-wrap');
    const exampleEl = document.getElementById('entry-modal-example');
    if (ex) {
        exampleWrap.classList.remove('hidden');
        exampleEl.textContent = ex;
    } else {
        exampleWrap.classList.add('hidden');
        exampleEl.textContent = '';
    }

    const sourceWrap = document.getElementById('entry-modal-source-wrap');
    if (dictLabel && dictUrl) {
        const link = document.getElementById('entry-modal-source');
        link.textContent = 'Словарь: ' + dictLabel + ' →';
        link.href = dictUrl;
        sourceWrap.classList.remove('hidden');
    } else {
        sourceWrap.classList.add('hidden');
    }

    const actionsWrap = document.getElementById('entry-modal-actions');
    const editLink = document.getElementById('entry-modal-edit');
    if (editUrl) {
        editLink.href = editUrl;
        actionsWrap.classList.remove('hidden');
    } else {
        actionsWrap.classList.add('hidden');
    }

    openModal('entry-detail-modal');
}
</script>
@endpush
@endonce
