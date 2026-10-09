import { initLightbox } from './landing/lightbox';
import { initThemeShowcase } from './landing/showcase';
import { initThemeToggle } from './theme-toggle';

document.addEventListener('DOMContentLoaded', (): void => {
    initThemeToggle('theme-toggle');
    initThemeShowcase();
    initLightbox();
});
