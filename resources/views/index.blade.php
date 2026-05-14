<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resume Manager</title>

    {{-- Vite Assets --}}
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])

    {{-- Google Fonts: Space Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Space Mono', monospace;
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">

    {{-- Navigation Bar --}}
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md">
        <div class="container mx-auto max-w-4xl px-8 py-6 flex justify-between items-center">
            <div class="text-xl font-bold tracking-tight">
                Resume Manager<span class="text-sky-600">.</span>
            </div>
            <div class="flex space-x-6 items-center">
                <a href="{{ route('resume', ['user' => 'juaniquillo']) }}" class="text-sm font-bold hover:text-sky-600 transition-colors">Resume</a>
                <a href="#features" class="text-sm font-bold hover:text-sky-600 transition-colors hidden md:block">Features</a>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-sky-600 hover:text-sky-700">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold hover:text-sky-600 transition-colors">Log in</a>
                    @endauth
                @endif

                {{-- Dark Mode Toggle Button --}}
                <button id="theme-toggle" aria-label="Toggle color mode" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-sky-100 dark:hover:bg-sky-900/30 transition-colors focus:outline-none">
                    <svg id="theme-icon" class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

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

    {{-- Footer --}}
    <footer class="py-12 border-t border-gray-100 dark:border-gray-800">
        <div class="container mx-auto max-w-4xl px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Resume Manager. Built with CrudAssistant.
            </div>
            <div class="flex space-x-6 text-sm font-bold">
                <a href="https://github.com/juaniquillo/resume" class="hover:text-sky-600 transition-colors">Repository</a>
                <a href="https://github.com/juaniquillo/crud-assistant" class="hover:text-sky-600 transition-colors">CrudAssistant</a>
            </div>
        </div>
    </footer>

</body>
</html>
