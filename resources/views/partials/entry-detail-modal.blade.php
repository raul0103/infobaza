@once
@push('modals')
<x-modal id="entry-detail-modal" title="Слово" size="lg">
    <div id="entry-modal-definition" class="markdown-body text-gray-800 mb-4"></div>
    <div id="entry-modal-example-wrap" class="hidden border-t border-gray-100 pt-4">
        <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Пример</p>
        <div id="entry-modal-example" class="markdown-body text-gray-600 italic"></div>
    </div>
    <div id="entry-modal-source-wrap" class="hidden mt-4 border-t border-gray-100 pt-4">
        <a id="entry-modal-source" href="#" class="link"></a>
    </div>
</x-modal>
@endpush

@push('scripts')
<script>
function openEntryModal(termOrEl, definition, example, dictionaryLabel, dictionaryUrl) {
    let term = termOrEl;
    let def = definition || '';
    let defHtml = '';
    let ex = example || '';
    let exHtml = '';
    let dictLabel = dictionaryLabel || '';
    let dictUrl = dictionaryUrl || '';

    if (termOrEl && typeof termOrEl === 'object' && termOrEl.dataset) {
        const d = termOrEl.dataset;
        const decode = (v) => decodeURIComponent(v || '');
        term = decode(d.term);
        def = decode(d.definition);
        defHtml = decode(d.definitionHtml || '');
        ex = decode(d.example);
        exHtml = decode(d.exampleHtml || '');
        dictLabel = decode(d.dictionaryLabel);
        dictUrl = d.dictionaryUrl || '';
    }

    document.getElementById('entry-detail-modal-title').textContent = term;
    const defEl = document.getElementById('entry-modal-definition');
    if (defHtml) {
        defEl.innerHTML = defHtml;
    } else {
        defEl.textContent = def;
    }

    const exampleWrap = document.getElementById('entry-modal-example-wrap');
    const exampleEl = document.getElementById('entry-modal-example');
    if (ex || exHtml) {
        exampleWrap.classList.remove('hidden');
        if (exHtml) {
            exampleEl.innerHTML = exHtml;
        } else {
            exampleEl.textContent = ex;
        }
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

    openModal('entry-detail-modal');
}
</script>
@endpush
@endonce
