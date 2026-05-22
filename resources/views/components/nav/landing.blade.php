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

            {{-- Dark Mode Toggle Component --}}
            <x-theme-toggle />
        </div>
    </div>
</nav>
