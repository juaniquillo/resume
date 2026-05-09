<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth"> {{-- Added scroll-smooth --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resume Manager</title>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/landing.js'])

    {{-- Google Fonts: Space Mono for a technical, retro, pixelated aesthetic --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <style>
        /* Apply Space Mono font globally */
        body {
            font-family: 'Space Mono', monospace;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Mono', monospace;
        }
    </style>
</head>
<body class="antialiased dark:bg-gray-900 dark:text-white">

    {{-- Navigation Bar --}}
    <nav class="bg-gray-800 dark:bg-gray-900 text-white p-4 shadow-md sticky top-0 z-50"> {{-- Added sticky positioning --}}
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold">
                Resume Manager
            </div>
            <div class="flex space-x-4 items-center"> {{-- Added items-center for vertical alignment --}}
                <a href="#features" class="hover:text-gray-300">Features</a>
                {{-- Assuming a login/register link might be relevant for a landing page --}}
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="hover:text-gray-300">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-gray-300">Log in</a>
                    @endauth
                @endif
                {{-- Dark Mode Toggle Button --}}
                <button id="theme-toggle" aria-label="Toggle color mode" class="p-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <svg id="theme-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        {{-- Default icon will be set by JS --}}
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 12.011a9 9 0 01-8.386 8.386L12 21l-3.646-3.646a9.003 9.003 0 018.386-8.386zm0 0l-.001-.001h-.001zm-4.094-3.613a6 6 0 00-7.482 0M9.5 16a9 9 0 0111.386-8.386L12 3"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <header class="bg-gradient-to-r from-sky-700 to-sky-900 dark:from-gray-800 dark:to-gray-900 text-white py-20 px-4 text-center">
        <div class="container mx-auto max-w-4xl px-4">
            <h1 class="text-3xl md:text-5xl leading-normal font-bold mb-4">
                Craft Your Professional Story Effortlessly
            </h1>
            <p class="text-xl mb-8">
                Manage your resume sections with ease, from experience to education, and export in industry-standard formats.
            </p>
            <a href="{{ route('login') }}" class="bg-emerald-700 hover:bg-emerald-800 border border-emerald-800 shadow-xl text-white text-shadow-2xs font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                Login
            </a>
        </div>
    </header>

    {{-- Features Section --}}
    <section id="features" class="py-20 container mx-auto max-w-4xl px-4">
        <div class="text-center px-6">
            <h2 class="text-4xl font-bold mb-12">Why Choose Resume Manager?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- Feature 1 --}}
                <div class="bg-gray-100 dark:bg-transparent p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-4">Intuitive Management</h3>
                    <p class="text-xl">
                        Easily add, edit, and organize all sections of your resume using a clean, component-based interface.
                    </p>
                </div>
                {{-- Feature 2 --}}
                <div class="bg-gray-100 dark:bg-transparent p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-4">Standardized Exports</h3>
                    <p class="text-xl">
                        Generate your resume in industry-standard JSON formats, ensuring compatibility with ATS and other platforms.
                    </p>
                </div>
                {{-- Feature 3 --}}
                <div class="bg-gray-100 dark:bg-transparent p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-4">Secure & Robust</h3>
                    <p class="text-xl">
                        Built with Laravel's security best practices, including UUIDs and a robust backend for reliable data handling.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-800 dark:bg-gray-900 text-white py-8 px-4 text-center">
        <div class="container mx-auto max-w-4xl px-4">
            <p>&copy; {{ date('Y') }} Resume Manager. All rights reserved.</p>
            <p class="mt-2">
                <a href="https://github.com/juaniquillo/resume" class="hover:text-gray-300">Project Repository</a> |
                <a href="https://github.com/juaniquillo/crud-assistant" class="hover:text-gray-300">CrudAssistant</a>
            </p>
        </div>
    </footer>

</body>
</html>