<nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md">
    <div class="container mx-auto max-w-4xl px-8 py-6 flex justify-between items-center">
        <div class="text-xl font-bold tracking-tight">
            Resume Manager<span class="text-sky-600">.</span>
        </div>
        <div class="flex space-x-6 items-center">
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
