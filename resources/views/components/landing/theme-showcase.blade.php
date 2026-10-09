@props([
    'themes' => [],
])

<div data-theme-showcase class="w-full">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl shadow-gray-900/5 dark:border-gray-800 dark:bg-gray-950">
        <div class="flex items-center gap-4 px-5 py-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Theme preview</p>
                <h2 data-showcase-label class="flex min-h-14 items-center text-lg font-bold text-gray-900 dark:text-white">{{ $themes[0]['label'] ?? '' }}</h2>
            </div>
        </div>

        <button
            type="button"
            data-lightbox-open
            data-lightbox-src="{{ asset('images/themes/' . ($themes[0]['key'] ?? 'default') . '.png') }}"
            data-lightbox-alt="{{ ($themes[0]['label'] ?? 'Resume') }} resume theme, enlarged view"
            class="relative block h-110 w-full cursor-zoom-in overflow-hidden border-y border-gray-100 sm:h-[520px] dark:border-gray-800"
            aria-label="Enlarge resume theme preview"
        >
            @foreach ($themes as $index => $theme)
                <img
                    src="{{ asset('images/themes/' . $theme['key'] . '.png') }}"
                    data-showcase-image="{{ $theme['key'] }}"
                    data-light-src="{{ asset('images/themes/' . $theme['key'] . '.png') }}"
                    data-dark-src="{{ asset('images/themes/' . $theme['key'] . '-dark.png') }}"
                    width="1200"
                    height="1600"
                    alt="{{ $theme['label'] }} resume theme preview"
                    @if ($index > 0) hidden loading="lazy" aria-hidden="true" @else fetchpriority="high" @endif
                    class="absolute inset-x-0 top-[-4%] w-full select-none transition-opacity duration-300 motion-reduce:transition-none {{ $index > 0 ? 'opacity-0' : 'opacity-100' }}"
                    draggable="false"
                />
            @endforeach
            <span class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-linear-to-t from-white to-transparent dark:from-gray-950"></span>
        </button>

        <div class="flex flex-wrap gap-2 px-5 py-4" role="tablist" aria-label="Resume themes">
            @foreach ($themes as $index => $theme)
                <button
                    type="button"
                    role="tab"
                    data-showcase-tab="{{ $theme['key'] }}"
                    data-showcase-label="{{ $theme['label'] }}"
                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                    @class([
                        'rounded-full px-4 py-1.5 text-sm font-bold transition',
                        'bg-gray-300 text-gray-900 hover:bg-gray-300 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-600' => $index === 0,
                        'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' => $index !== 0,
                    ])
                >{{ $theme['tab'] }}</button>
            @endforeach
        </div>
    </div>

    <div data-lightbox hidden class="fixed inset-0 z-100 bg-gray-950/80 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Enlarged resume theme preview">
        <div data-zoom-viewport class="absolute inset-0 flex touch-none items-center justify-center overflow-hidden p-4 pt-20 select-none sm:p-8">
            <img data-lightbox-image src="" alt="" class="max-h-full w-auto max-w-full rounded-xl shadow-2xl" draggable="false" />
        </div>
        <div class="absolute left-4 top-4 flex gap-2">
            <button type="button" data-zoom-in class="rounded-xl bg-white px-3 py-2 text-sm font-bold text-gray-900 hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700" aria-label="Zoom in">
                +
            </button>
            <button type="button" data-zoom-out class="rounded-xl bg-white px-3 py-2 text-sm font-bold text-gray-900 hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700" aria-label="Zoom out">
                &minus;
            </button>
            <button type="button" data-zoom-reset class="rounded-xl bg-white px-3 py-2 text-sm font-bold text-gray-900 hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700" aria-label="Reset zoom">
                Reset
            </button>
        </div>
        <button type="button" data-lightbox-close class="absolute right-4 top-4 rounded-xl bg-white px-4 py-2 text-sm font-bold text-gray-900 hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700" aria-label="Close enlarged view">
            &times; Close
        </button>
    </div>
</div>
