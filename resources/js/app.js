document.addEventListener('livewire:navigated', () => {
    const el = document.querySelector('[data-sortable]');

    if (!el) return;

    Sortable.create(el, {
        animation: 150,

        onEnd: () => {
            let order = [];
            const items = el.querySelectorAll('[data-id]');
            const total = items.length;

            items.forEach((item, index) => {
                order.push({
                    id: item.dataset.id,
                    position: total - index
                });
            });

            Livewire.dispatch('updateOrder', { order });
        }
    });
});
