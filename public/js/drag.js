document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.drag-item');
    const zones = document.querySelectorAll('.fix-wrapper.drag-content');

    let draggedItem = null;

    // Drag Start
    items.forEach(item => {
        item.setAttribute('draggable', 'true');

        item.addEventListener('dragstart', function (e) {
            draggedItem = item;
            setTimeout(() => item.style.display = 'none', 0);
        });

        item.addEventListener('dragend', function () {
            setTimeout(() => {
                draggedItem.style.display = 'block';
                draggedItem = null;
            }, 0);
        });
    });

    // Drop Zone Events
    zones.forEach(zone => {
        zone.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });

        zone.addEventListener('dragleave', function () {
            this.classList.remove('drag-over');
        });

        zone.addEventListener('drop', function (e) {
           e.preventDefault();
            this.classList.remove('drag-over');

            if (draggedItem) {
                this.appendChild(draggedItem);

                // Alle drag-items in dieser Zone
                const allItems = this.querySelectorAll('.drag-item');
                const updated = [];

                allItems.forEach((item, index) => {
                    updated.push({
                        id: item.dataset.elementId,
                        inColumn: this.id.replace('fly_ux_', ''),
                        sorting: (index + 1) * 128 // Symfony / Contao nutzt oft 128er Schritte
                    });
                });

                // AJAX-Request an dein Backend
                fetch('/contao/_flyux/update-sorting', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ updates: updated })
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Sortierung gespeichert:', data);
                })
                .catch(err => console.error('Fehler:', err));
            }
        });
    });
});

