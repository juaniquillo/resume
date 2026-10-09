const ACTIVE_TAB_CLASSES = [
    'bg-gray-300',
    'text-gray-900',
    'dark:bg-gray-600',
    'dark:text-white',
    'hover:bg-gray-300',
    'dark:hover:bg-gray-600',
];
const INACTIVE_TAB_CLASSES = [
    'bg-gray-100',
    'text-gray-700',
    'dark:bg-gray-800',
    'dark:text-gray-300',
    'hover:bg-gray-200',
    'dark:hover:bg-gray-700',
];
const FADE_DURATION_MS = 300;

/**
 * Single-image theme showcase beside the landing hero.
 *
 * Pill tabs swap the visible screenshot with a crossfade once the target
 * image has loaded (instant swap on first view to preserve lazy loading, and
 * always instant when reduced motion is preferred). The light/dark variant
 * follows the site-wide dark mode toggle and the lightbox trigger stays in
 * sync with the active theme so the enlarged view always matches.
 */
export function initThemeShowcase(): void {
    const showcase = document.querySelector<HTMLElement>('[data-theme-showcase]');

    if (!showcase) {
        return;
    }

    const images = [...showcase.querySelectorAll<HTMLImageElement>('[data-showcase-image]')];
    const tabs = [...showcase.querySelectorAll<HTMLButtonElement>('[data-showcase-tab]')];
    const label = showcase.querySelector<HTMLElement>('[data-showcase-label]');
    const opener = showcase.querySelector<HTMLElement>('[data-lightbox-open]');

    if (images.length === 0 || tabs.length === 0) {
        return;
    }

    let currentTheme = images[0].getAttribute('data-showcase-image') ?? 'default';
    let generation = 0;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isDarkMode = (): boolean => document.documentElement.classList.contains('dark');

    const markLoaded = (image: HTMLImageElement): void => {
        image.dataset.loaded = 'true';
    };

    images.forEach((image) => {
        if (image.complete && image.naturalWidth > 0) {
            markLoaded(image);
        } else {
            image.addEventListener('load', () => markLoaded(image), { once: true });
        }
    });

    const imageFor = (theme: string): HTMLImageElement | undefined => {
        return images.find((image) => image.getAttribute('data-showcase-image') === theme);
    };

    const variantSrc = (image: HTMLImageElement): string => {
        return image.getAttribute(isDarkMode() ? 'data-dark-src' : 'data-light-src') ?? image.src;
    };

    const syncVariant = (): void => {
        const current = imageFor(currentTheme);

        if (!current) {
            return;
        }

        const src = variantSrc(current);
        current.src = src;
        opener?.setAttribute('data-lightbox-src', src);
    };

    const styleTab = (tab: HTMLButtonElement, isActive: boolean): void => {
        tab.setAttribute('aria-selected', isActive ? 'true' : 'false');

        ACTIVE_TAB_CLASSES.forEach((className) => tab.classList.toggle(className, isActive));
        INACTIVE_TAB_CLASSES.forEach((className) => tab.classList.toggle(className, !isActive));
    };

    const showInstantly = (target: HTMLImageElement, previous: HTMLImageElement | undefined): void => {
        previous?.classList.add('opacity-0');
        previous?.classList.remove('opacity-100');
        previous?.setAttribute('aria-hidden', 'true');

        if (previous && previous !== target) {
            previous.hidden = true;
        }

        target.hidden = false;
        target.classList.remove('opacity-0');
        target.classList.add('opacity-100');
        target.removeAttribute('aria-hidden');
    };

    const crossfade = (target: HTMLImageElement, previous: HTMLImageElement | undefined): void => {
        generation += 1;
        const ticket = generation;

        target.hidden = false;
        target.removeAttribute('aria-hidden');

        window.requestAnimationFrame((): void => {
            if (ticket !== generation) {
                return;
            }

            target.classList.remove('opacity-0');
            target.classList.add('opacity-100');
            previous?.classList.add('opacity-0');
            previous?.classList.remove('opacity-100');
            previous?.setAttribute('aria-hidden', 'true');
        });

        window.setTimeout((): void => {
            if (ticket !== generation) {
                return;
            }

            images.forEach((image) => {
                if (image !== target) {
                    image.hidden = true;
                }
            });
        }, FADE_DURATION_MS + 50);
    };

    const activate = (theme: string): void => {
        const target = imageFor(theme);

        if (!target) {
            return;
        }

        const previous = imageFor(currentTheme);
        currentTheme = theme;

        if (previous !== undefined && previous !== target) {
            if (reduceMotion || target.dataset.loaded !== 'true') {
                showInstantly(target, previous);
            } else {
                crossfade(target, previous);
            }
        }

        tabs.forEach((tab) => {
            styleTab(tab, tab.getAttribute('data-showcase-tab') === theme);
        });

        const activeTab = tabs.find((tab) => tab.getAttribute('data-showcase-tab') === theme);

        if (label && activeTab) {
            label.textContent = activeTab.getAttribute('data-showcase-label') ?? theme;
        }

        if (opener && activeTab) {
            opener.setAttribute(
                'data-lightbox-alt',
                `${activeTab.getAttribute('data-showcase-label') ?? theme} resume theme, enlarged view`,
            );
        }

        syncVariant();
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', (): void => {
            const theme = tab.getAttribute('data-showcase-tab');

            if (theme) {
                activate(theme);
            }
        });
    });

    new MutationObserver(syncVariant).observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });

    activate(currentTheme);
}
