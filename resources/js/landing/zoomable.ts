const MIN_SCALE = 1;
const CENTER_ZOOM_STEP = 1.5;
const WHEEL_ZOOM_SPEED = 0.002;

export interface ZoomController {
    reset(): void;
    zoomIn(): void;
    zoomOut(): void;
    toggleFullSize(): void;
}

interface ViewportGeometry {
    left: number;
    top: number;
    width: number;
    height: number;
}

/**
 * Pointer-anchored pan/zoom for a single image inside a viewport element.
 *
 * Scaling happens about the image center, so an unpanned image is always
 * visually centered, and zooming with the cursor (or pinch midpoint) held
 * still keeps the content under it stationary. All geometry is measured
 * against the viewport content box, so asymmetric padding never shifts the
 * pan bounds. Supports mouse-wheel zoom, drag panning, two-finger pinch
 * (Pointer Events) and programmatic center zoom for buttons and
 * double-click. Scale is capped at the image's natural size. Call reset()
 * whenever the image source changes or the surrounding overlay
 * opens/closes.
 */
export function initZoomable(image: HTMLImageElement, viewport: HTMLElement): ZoomController {
    let scale = MIN_SCALE;
    let translateX = 0;
    let translateY = 0;
    const pointers = new Map<number, { x: number; y: number }>();
    let pinchStart: { distance: number; scale: number } | null = null;
    let lastMidpoint: { x: number; y: number } | null = null;
    let dragging = false;

    image.style.transformOrigin = '50% 50%';

    const geometry = (): ViewportGeometry => {
        const box = viewport.getBoundingClientRect();
        const style = window.getComputedStyle(viewport);
        const padLeft = parseFloat(style.paddingLeft || '0');
        const padTop = parseFloat(style.paddingTop || '0');
        const padRight = parseFloat(style.paddingRight || '0');
        const padBottom = parseFloat(style.paddingBottom || '0');

        return {
            left: box.left + padLeft,
            top: box.top + padTop,
            width: Math.max(0, box.width - padLeft - padRight),
            height: Math.max(0, box.height - padTop - padBottom),
        };
    };

    const maxScale = (): number => {
        if (!image.naturalWidth || !image.clientWidth) {
            return MIN_SCALE;
        }

        return Math.max(MIN_SCALE, image.naturalWidth / image.clientWidth);
    };

    const apply = (): void => {
        const view = geometry();
        const overflowX = Math.max(0, (image.clientWidth * scale - view.width) / 2);
        const overflowY = Math.max(0, (image.clientHeight * scale - view.height) / 2);

        translateX = Math.min(overflowX, Math.max(-overflowX, translateX));
        translateY = Math.min(overflowY, Math.max(-overflowY, translateY));

        if (scale <= MIN_SCALE) {
            translateX = 0;
            translateY = 0;
        }

        image.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
        image.style.cursor = scale > MIN_SCALE ? 'grab' : '';
    };

    const zoomAt = (clientX: number, clientY: number, factor: number): void => {
        const box = image.getBoundingClientRect();

        if (box.width === 0) {
            return;
        }

        const next = Math.min(maxScale(), Math.max(MIN_SCALE, scale * factor));

        if (next === scale) {
            return;
        }

        const centerX = box.left + box.width / 2;
        const centerY = box.top + box.height / 2;
        const ratio = next / scale;
        translateX += (clientX - centerX) * (1 - ratio);
        translateY += (clientY - centerY) * (1 - ratio);
        scale = next;
        apply();
    };

    const zoomCentered = (factor: number): void => {
        const view = geometry();
        zoomAt(view.left + view.width / 2, view.top + view.height / 2, factor);
    };

    const reset = (): void => {
        scale = MIN_SCALE;
        translateX = 0;
        translateY = 0;
        pointers.clear();
        pinchStart = null;
        lastMidpoint = null;
        dragging = false;
        apply();
    };

    viewport.addEventListener(
        'wheel',
        (event: Event): void => {
            const wheelEvent = event as WheelEvent;
            wheelEvent.preventDefault();
            zoomAt(wheelEvent.clientX, wheelEvent.clientY, Math.exp(-wheelEvent.deltaY * WHEEL_ZOOM_SPEED));
        },
        { passive: false },
    );

    const midpoint = (): { x: number; y: number } | null => {
        if (pointers.size !== 2) {
            return null;
        }

        const [first, second] = [...pointers.values()];

        return { x: (first.x + second.x) / 2, y: (first.y + second.y) / 2 };
    };

    viewport.addEventListener('pointerdown', (event: PointerEvent): void => {
        pointers.set(event.pointerId, { x: event.clientX, y: event.clientY });

        if (pointers.size === 2) {
            const [first, second] = [...pointers.values()];
            pinchStart = {
                distance: Math.hypot(first.x - second.x, first.y - second.y),
                scale,
            };
            lastMidpoint = midpoint();
            dragging = false;
            image.style.cursor = '';
        } else if (scale > MIN_SCALE) {
            dragging = true;
            image.style.cursor = 'grabbing';
        }
    });

    viewport.addEventListener('pointermove', (event: PointerEvent): void => {
        const previous = pointers.get(event.pointerId);

        if (!previous) {
            return;
        }

        pointers.set(event.pointerId, { x: event.clientX, y: event.clientY });

        if (pointers.size === 2 && pinchStart && pinchStart.distance > 0) {
            const [first, second] = [...pointers.values()];
            const distance = Math.hypot(first.x - second.x, first.y - second.y);
            const next = Math.min(maxScale(), Math.max(MIN_SCALE, (pinchStart.scale * distance) / pinchStart.distance));
            const middle = midpoint();

            if (!middle) {
                return;
            }

            const anchor = lastMidpoint ?? middle;
            const box = image.getBoundingClientRect();
            const centerX = box.left + box.width / 2;
            const centerY = box.top + box.height / 2;
            const ratio = next / scale;
            translateX += (anchor.x - centerX) * (1 - ratio);
            translateY += (anchor.y - centerY) * (1 - ratio);
            scale = next;
            translateX += middle.x - anchor.x;
            translateY += middle.y - anchor.y;
            lastMidpoint = middle;
            apply();
        } else if (dragging) {
            translateX += event.clientX - previous.x;
            translateY += event.clientY - previous.y;
            apply();
        }
    });

    const release = (event: PointerEvent): void => {
        pointers.delete(event.pointerId);
        pinchStart = null;
        lastMidpoint = null;
        dragging = false;

        if (scale > MIN_SCALE) {
            image.style.cursor = 'grab';
        }
    };

    viewport.addEventListener('pointerup', release);
    viewport.addEventListener('pointercancel', release);

    viewport.addEventListener('dblclick', (event: MouseEvent): void => {
        event.preventDefault();

        if (scale > MIN_SCALE) {
            reset();
        } else {
            zoomAt(event.clientX, event.clientY, maxScale());
        }
    });

    return {
        reset,
        zoomIn: () => zoomCentered(CENTER_ZOOM_STEP),
        zoomOut: () => zoomCentered(1 / CENTER_ZOOM_STEP),
        toggleFullSize: () => {
            if (scale > MIN_SCALE) {
                reset();
            } else {
                zoomCentered(maxScale());
            }
        },
    };
}
