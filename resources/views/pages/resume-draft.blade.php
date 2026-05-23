<x-layouts.guest
    :title="($user->name ?? 'User') . ' - Resume Unavailable'"
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
                <flux:button href="{{ route('home') }}" variant="primary">
                    Return Home
                </flux:button>
            </div>
        </div>
    </div>
</x-layouts.guest>
