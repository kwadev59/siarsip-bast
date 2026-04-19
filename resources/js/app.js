import './bootstrap';

const ready = () => {
    document.body.classList.add('page-ready');
};

const setupDrawer = () => {
    const root = document.querySelector('[data-mobile-nav]');

    if (!root) {
        return;
    }

    const panel = root.querySelector('[data-mobile-panel]');
    const backdrop = root.querySelector('[data-mobile-backdrop]');
    const openButtons = document.querySelectorAll('[data-mobile-open]');
    const closeButtons = root.querySelectorAll('[data-mobile-close]');

    const setOpen = (open) => {
        root.dataset.open = open ? 'true' : 'false';
        document.body.classList.toggle('overflow-hidden', open);
        panel?.classList.toggle('-translate-x-full', !open);
        panel?.classList.toggle('translate-x-0', open);
        backdrop?.classList.toggle('pointer-events-none', !open);
        backdrop?.classList.toggle('opacity-0', !open);
        backdrop?.classList.toggle('pointer-events-auto', open);
        backdrop?.classList.toggle('opacity-100', open);
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => setOpen(true));
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', () => setOpen(false));
    });

    backdrop?.addEventListener('click', () => setOpen(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });

    panel?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    setOpen(false);
};

const setupModals = () => {
    const modals = document.querySelectorAll('[data-modal]');

    modals.forEach((modal) => {
        const panel = modal.querySelector('[data-modal-panel]');
        const backdrop = modal.querySelector('[data-modal-backdrop]');
        const openButtons = document.querySelectorAll(`[data-modal-open="${modal.id}"]`);
        const closeButtons = modal.querySelectorAll('[data-modal-close]');

        let closeTimer = null;

        const setOpen = (open) => {
            if (closeTimer) {
                clearTimeout(closeTimer);
                closeTimer = null;
            }

            if (open) {
                modal.dataset.open = 'true';
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                requestAnimationFrame(() => {
                    modal.dataset.open = 'true';
                });
                return;
            }

            modal.dataset.open = 'false';
            document.body.classList.remove('overflow-hidden');
            closeTimer = window.setTimeout(() => {
                modal.classList.add('hidden');
            }, 180);
        };

        openButtons.forEach((button) => {
            button.addEventListener('click', () => setOpen(true));
        });

        closeButtons.forEach((button) => {
            button.addEventListener('click', () => setOpen(false));
        });

        backdrop?.addEventListener('click', () => setOpen(false));

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.dataset.open === 'true') {
                setOpen(false);
            }
        });

        if (modal.dataset.open === 'true') {
            setOpen(true);
        } else {
            modal.classList.add('hidden');
        }

        panel?.querySelectorAll('a, button, input, select, textarea').forEach((element) => {
            element.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    setOpen(false);
                }
            });
        });
    });
};

const setupDocumentNumberPreview = () => {
    const previews = document.querySelectorAll('[data-document-number-preview]');

    if (!previews.length) {
        return;
    }

    const month = String(new Date().getMonth() + 1).padStart(2, '0');
    const year = String(new Date().getFullYear());

    previews.forEach((preview) => {
        const container = preview.closest('form') ?? document;
        const divisionSelect = container.querySelector('[data-division-select]');

        if (!divisionSelect) {
            return;
        }

        const updatePreview = () => {
            const selectedLabel = divisionSelect.options[divisionSelect.selectedIndex]?.text ?? 'DIVISI';
            const divisionCode = selectedLabel
                .trim()
                .toUpperCase()
                .replace(/[^A-Z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '') || 'DIVISI';

            preview.textContent = `Akan dibuat otomatis: 0000-${divisionCode}-PT.BIM-PPS-${month}-${year}`;
        };

        divisionSelect.addEventListener('change', updatePreview);
        updatePreview();
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ready);
    document.addEventListener('DOMContentLoaded', setupDrawer);
    document.addEventListener('DOMContentLoaded', setupModals);
    document.addEventListener('DOMContentLoaded', setupDocumentNumberPreview);
} else {
    ready();
    setupDrawer();
    setupModals();
    setupDocumentNumberPreview();
}
