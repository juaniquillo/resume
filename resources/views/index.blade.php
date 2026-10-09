<x-layouts.guest
    title="Resume Manager"
    description="Own your career narrative: manage every resume section in one dashboard, switch between six professional themes, and export JSON Resume or themed PDF files."
    :assets="['resources/css/landing.css', 'resources/js/landing.ts']"
    :image="asset('home-og.png')"
>
    <x-slot:nav>
        <x-nav.landing />
    </x-slot:nav>

    {{-- Hero Section --}}
    <header class="py-12 md:py-28">
        <div class="container mx-auto max-w-5xl 3xl:max-w-6xl px-8 grid gap-12 lg:grid-cols-2">
            <div>
                <h1 class="text-5xl md:text-6xl 3xl:text-7xl font-bold tracking-tight text-gray-900 dark:text-white leading-tight">
                    Craft Your Professional Story<span class="text-sky-600">.</span>
                </h1>
                <p class="text-2xl 3xl:text-3xl font-medium text-sky-600 dark:text-sky-400 mt-8 max-w-2xl 3xl:max-w-3xl">
                    One dashboard for your entire career — experience, highlights, skills and cover letters — exported as JSON Resume or a beautifully themed PDF.
                </p>
                <div class="flex flex-wrap gap-4 mt-10">
                    <a href="{{ route('login') }}" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-4 px-10 rounded-2xl shadow-lg shadow-sky-600/20 transition duration-300 text-lg">
                        @auth Go to Dashboard @else Start Building @endauth
                    </a>
                    @if ($demo)
                        <a href="{{ route('resume', ['user' => $demo]) }}" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-bold py-4 px-10 rounded-2xl transition duration-300 text-lg">
                            View Demo
                        </a>
                    @endif
                </div>
            </div>
            <x-landing.theme-showcase :themes="$themeShowcase" />
        </div>
    </header>

    {{-- Features Section --}}
    <section id="features" class="py-14">
        <div class="container mx-auto max-w-5xl 3xl:max-w-6xl px-8">
            <h2 class="text-3xl font-bold border-b-2 border-sky-600 pb-2 mb-12 uppercase tracking-wider dark:text-white dark:border-sky-500">
                Full Control Over Your Profile
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                {{-- Feature 1 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Every Section, One Dashboard
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Work history, education, skills, languages, projects, awards and more — managed from a single intuitive dashboard.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Highlights That Prove Impact
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Attach measurable achievements to every role and project — because your outcomes matter more than your titles.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Tailor It Per Application
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Reorder sections with drag-and-drop and toggle visibility, so each application emphasizes the right strengths.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Six Themes, One Click
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        From Retro-Modern to Terminal Console, switch your resume's entire look in the preview above — then export it as a themed PDF.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        JSON Resume Standard
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Import and export portable JSON Resume files, with public download links for your PDF and JSON versions.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Share-Ready Profile
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Publish a public link with cover letters and custom social preview images that look sharp on LinkedIn and X.
                    </p>
                </div>
            </div>

            <p class="mt-12 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                Built with Laravel &middot; Your data stays under your control &middot; Open source
            </p>
        </div>
    </section>

    {{-- Personal Section --}}
    <section id="about" class="py-20 bg-gray-50 dark:bg-zinc-900/50">
        <div class="container mx-auto max-w-5xl 3xl:max-w-6xl px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Why I Built This
            </h2>
            <p class="text-xl text-gray-700 dark:text-gray-300 mb-8 leading-relaxed max-w-2xl mx-auto">
                This isn't a commercial product. I built this tool to help me manage my own resume after recently losing my job. It's my personal "command center" for my professional journey, and I've opened it up for anyone who might find it useful.
            </p>
            <div class="inline-block p-6 rounded-2xl border-2 border-sky-600/20 dark:border-sky-500/20">
                <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">Want to try it out or have feedback?</p>
                <a href="https://x.com/juaniquillo" target="_blank" rel="noopener noreferrer" class="text-xl font-bold text-sky-600 dark:text-sky-400 hover:underline">
                    @juaniquillo
                </a>
            </div>
        </div>
    </section>

    <x-slot:footer>
        <x-footer />
    </x-slot:footer>
</x-layouts.guest>
