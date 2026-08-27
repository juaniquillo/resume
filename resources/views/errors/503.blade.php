<x-layouts.guest
    :title="__('Service Unavailable')"
    :noindex="true"
>
    <div class="min-h-screen flex items-center justify-center p-6 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-md w-full text-center space-y-6">
            <div class="flex justify-center">
                <flux:icon.wrench-screwdriver class="size-20 text-sky-600 dark:text-sky-500" />
            </div>

            <div class="space-y-2">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ __('Service Unavailable') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    {{ __('The application is currently undergoing maintenance or is overloaded. Please check back soon.') }}
                </p>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url()->previous() }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-4 px-8 rounded-2xl transition duration-300 text-lg w-full sm:w-auto text-center">
                    {{ __('Go Back') }}
                </a>
                <a href="{{ route('home') }}" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-sky-600/20 transition duration-300 text-lg w-full sm:w-auto text-center">
                    {{ __('Return Home') }}
                </a>
            </div>
        </div>
    </div>
</x-layouts.guest>
