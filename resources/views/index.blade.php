<x-layouts.guest
    title="Resume Manager"
    :assets="['resources/css/landing.css', 'resources/js/landing.js']"
>
    <x-slot:nav>
        <x-nav.landing />
    </x-slot:nav>

    {{-- Hero Section --}}
    <header class="py-20 md:py-32">
        <div class="container mx-auto max-w-4xl px-8">
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight text-gray-900 dark:text-white mb-6 leading-tight">
                Craft Your Professional Story<span class="text-sky-600">.</span>
            </h1>
            <p class="text-2xl font-medium text-sky-600 dark:text-sky-400 mb-10 max-w-2xl">
                Effortlessly manage your resume sections and export in industry-standard formats.
            </p>
            <div class="flex flex-wrap gap-4">
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
    </header>

    {{-- Features Section --}}
    <section id="features" class="py-20">
        <div class="container mx-auto max-w-4xl px-8">
            <h2 class="text-3xl font-bold border-b-2 border-sky-600 pb-2 mb-12 uppercase tracking-wider dark:text-white dark:border-sky-500">
                Full Control Over Your Profile
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                {{-- Feature 1 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Drag-and-Drop Layout
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Customize your resume layout instantly. Reorder sections like Experience, Skills, and Projects using an intuitive interactive interface.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Beautiful Themes
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Choose from multiple professional themes—Default, Bold, or Elegant—to match your personal brand and the industry you're targeting.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        JSON & PDF Exports
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Export your data in industry-standard JSON Resume format or generate high-quality PDFs with your chosen theme.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Secure & Private
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Built with the latest Laravel framework, ensuring your data is handled with the highest security standards and remains under your control.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Personal Section --}}
    <section id="about" class="py-20 bg-gray-50 dark:bg-zinc-900/50">
        <div class="container mx-auto max-w-4xl px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Why I Built This
            </h2>
            <p class="text-xl text-gray-700 dark:text-gray-300 mb-8 leading-relaxed max-w-2xl mx-auto">
                This isn't a commercial product. I built this tool to help me manage my own resume after recently losing my job. It's my personal "command center" for my professional journey, and I've opened it up for anyone who might find it useful.
            </p>
            <div class="inline-block p-6 rounded-2xl border-2 border-sky-600/20 dark:border-sky-500/20">
                <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">Want to try it out or have feedback?</p>
                <a href="https://x.com/juaniquillo" target="_blank" class="text-xl font-bold text-sky-600 dark:text-sky-400 hover:underline">
                    @juaniquillo
                </a>
            </div>
        </div>
    </section>

    <x-slot:footer>
        <x-footer />
    </x-slot:footer>
</x-layouts.guest>
