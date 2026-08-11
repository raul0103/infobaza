<script>
async function openReviewCard(el) {
    if (el.dataset.reviewed === '1' || el.disabled) return;

    if (el.dataset.kind === 'fact' || el.dataset.kind === 'phrase') {
        openCardModal(el);
    } else {
        openEntryModal(el);
    }

    el.dataset.reviewed = '1';
    el.disabled = true;
    el.classList.add('opacity-40', 'grayscale', 'cursor-default');
    el.classList.remove('hover:border-blue-200', 'cursor-pointer');

    const url = el.dataset.answerUrl;
    if (!url) return;

    const csrf = el.closest('[data-csrf]')?.dataset.csrf
        || document.querySelector('meta[name="csrf-token"]')?.content
        || '';

    try {
        await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
    } catch (e) {
        // Карточка уже неактивна — повтор при следующей пачке
    }
}
</script>
