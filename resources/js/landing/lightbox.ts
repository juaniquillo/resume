import { initZoomable } from './zoomable';

/**
 * Generic lightbox overlay driven by data attributes.
 *
 * Any element with [data-lightbox-open] opens the overlay, using its
 * data-lightbox-src and data-lightbox-alt values for the enlarged image.
 * The image is zoomable (wheel, pinch, drag, double-click, buttons) via an
 * optional [data-zoom-viewport] wrapper with [data-zoom-in], [data-zoom-out]
 * and [data-zoom-reset] controls.
 */
export function initLightbox(root: ParentNode = document): void {
    const overlay = root.querySelector<HTMLElement>('[data-lightbox]');

    if (!overlay) {
        return;
    }

    const image = overlay.querySelector<HTMLImageElement>('[data-lightbox-image]');
    const viewport = overlay.querySelector<HTMLElement>('[data-zoom-viewport]');
    const closeButton = overlay.querySelector<HTMLElement>('[data-lightbox-close]');
    const triggers = [...root.querySelectorAll<HTMLElement>('[data-lightbox-open]')];

    if (!image || triggers.length === 0) {
        return;
    }

    const zoom = viewport ? initZoomable(image, viewport) : null;

    const open = (src: string, alt: string): void => {
        image.src = src;
        image.alt = alt;
        zoom?.reset();
        overlay.hidden = false;
        document.body.classList.add('overflow-hidden');
        closeButton?.focus();
    };

    const close = (): void => {
        zoom?.reset();
        overlay.hidden = true;
        document.body.classList.remove('overflow-hidden');
    };

    triggers.forEach((trigger) => {
        trigger.addEventListener('click', (): void => {
            const src = trigger.getAttribute('data-lightbox-src') ?? '';
            const alt = trigger.getAttribute('data-lightbox-alt') ?? '';

            if (src) {
                open(src, alt);
            }
        });
    });

    closeButton?.addEventListener('click', close);

    overlay.querySelector('[data-zoom-in]')?.addEventListener('click', (): void => zoom?.zoomIn());
    overlay.querySelector('[data-zoom-out]')?.addEventListener('click', (): void => zoom?.zoomOut());
    overlay.querySelector('[data-zoom-reset]')?.addEventListener('click', (): void => zoom?.reset());

    let pointerDownAt: { x: number; y: number } | null = null;

    overlay.addEventListener('pointerdown', (event: PointerEvent): void => {
        pointerDownAt = { x: event.clientX, y: event.clientY };
    });

    overlay.addEventListener('click', (event): void => {
        const mouseEvent = event as MouseEvent;
        const wasDrag =
            pointerDownAt !== null &&
            Math.hypot(mouseEvent.clientX - pointerDownAt.x, mouseEvent.clientY - pointerDownAt.y) > 6;
        pointerDownAt = null;

        if (!wasDrag && (event.target === overlay || (viewport !== null && event.target === viewport))) {
            close();
        }
    });

    document.addEventListener('keydown', (event): void => {
        if (event.key === 'Escape' && !overlay.hidden) {
            close();
        }
    });
}
