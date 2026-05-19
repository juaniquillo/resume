<x-layouts.guest
    title="Resume Manager"
    :assets="['resources/css/landing.css']"
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
                <a href="{{ route('resume', ['user' => 'juaniquillo']) }}" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-bold py-4 px-10 rounded-2xl transition duration-300 text-lg">
                    View Demo
                </a>
            </div>
        </div>
    </header>

    {{-- Features Section --}}
    <section id="features" class="py-20">
        <div class="container mx-auto max-w-4xl px-8">
            <h2 class="text-3xl font-bold border-b-2 border-sky-600 pb-2 mb-12 uppercase tracking-wider dark:text-white dark:border-sky-500">
                Why Resume Manager?
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                {{-- Feature 1 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Intuitive Management
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Easily add, edit, and organize all sections of your resume using a clean, component-based interface designed for efficiency.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Standardized Exports
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Generate your resume in industry-standard JSON formats, ensuring maximum compatibility with ATS and recruitment platforms.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-2 bg-sky-600 rounded-full"></span>
                        Secure & Robust
                    </h3>
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        Built with Laravel's security best practices. Your data is protected, utilizing UUIDs and a robust backend for reliable data handling.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <x-slot:footer>
        <x-footer />
    </x-slot:footer>
</x-layouts.guest>
