@props([
    'user' => null,
])
<footer class="py-12 border-t border-gray-100 dark:border-gray-800">
    <div class="container mx-auto max-w-4xl 2xl:max-w-6xl px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-gray-500 text-sm">
            @if($user)
                &copy; {{ date('Y') }} {{ $user->name }}. All rights reserved.
            @else
                &copy; {{ date('Y') }} Resume Manager. Built with CrudAssistant.
            @endif
        </div>
        <div class="flex space-x-6 text-sm font-bold">
            <a href="https://github.com/juaniquillo/resume" class="hover:text-sky-600 transition-colors">Repository</a>
            <a href="https://github.com/juaniquillo/crud-assistant" class="hover:text-sky-600 transition-colors">CrudAssistant</a>
        </div>
    </div>
</footer>
