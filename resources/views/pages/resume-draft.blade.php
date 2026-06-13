<x-layouts.guest
    :title="($user->name ?? 'User') . ' - Resume Unavailable'"
    :noindex="true"
>
    <div class="min-h-screen flex items-center justify-center p-6 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-md w-full text-center space-y-6">
            <div class="flex justify-center">
                <flux:icon.no-symbol class="size-20 text-sky-600 dark:text-sky-500" />
            </div>

            <div class="space-y-2">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Resume Unavailable
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    The resume for <strong>{{ $user->name }}</strong> is currently in draft mode and is not publicly available at this time.
                </p>
            </div>

            <div class="pt-4">
                <a href="{{ route('home') }}" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-4 px-10 rounded-2xl shadow-lg shadow-sky-600/20 transition duration-300 text-lg">
                    Return Home
                </a>
            </div>
        </div>
    </div>
</x-layouts.guest>
