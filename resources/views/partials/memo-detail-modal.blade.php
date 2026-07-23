@once
@push('modals')
<x-modal id="memo-detail-modal" title="Заметка" size="lg">
    <div id="memo-modal-content" class="whitespace-pre-wrap text-gray-800 leading-relaxed"></div>
    <div id="memo-modal-empty" class="hidden text-gray-500">Текст пока не добавлен.</div>
    <div id="memo-modal-source-wrap" class="hidden mt-4 border-t border-gray-100 pt-4">
        <a id="memo-modal-source" href="#" class="link"></a>
    </div>
    <div id="memo-modal-actions" class="mt-4 border-t border-gray-100 pt-4 flex flex-wrap gap-2">
        <a id="memo-modal-edit" href="#" class="btn btn-secondary text-sm">Редактировать</a>
    </div>
</x-modal>
@endpush

@push('scripts')
<script>
function openMemoModal(el) {
    const d = el.dataset;
    const decode = (value) => decodeURIComponent(value || '');
    const title = decode(d.title);
    const content = decode(d.content);
    const categoryLabel = decode(d.categoryLabel);
    const categoryUrl = d.categoryUrl || '';
    const editUrl = d.editUrl || '';

    document.getElementById('memo-detail-modal-title').textContent = title;

    const contentEl = document.getElementById('memo-modal-content');
    const emptyEl = document.getElementById('memo-modal-empty');
    if (content) {
        contentEl.textContent = content;
        contentEl.classList.remove('hidden');
        emptyEl.classList.add('hidden');
    } else {
        contentEl.textContent = '';
        contentEl.classList.add('hidden');
        emptyEl.classList.remove('hidden');
    }

    const sourceWrap = document.getElementById('memo-modal-source-wrap');
    if (categoryLabel && categoryUrl) {
        const link = document.getElementById('memo-modal-source');
        link.textContent = 'Категория: ' + categoryLabel + ' →';
        link.href = categoryUrl;
        sourceWrap.classList.remove('hidden');
    } else {
        sourceWrap.classList.add('hidden');
    }

    const editLink = document.getElementById('memo-modal-edit');
    if (editUrl) {
        editLink.href = editUrl;
        editLink.classList.remove('hidden');
    } else {
        editLink.classList.add('hidden');
    }

    openModal('memo-detail-modal');
}
</script>
@endpush
@endonce
