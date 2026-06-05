<div class="flex items-center justify-between">
    <flux:heading size="xl" level="1">{{ __("Resume Manager") }}</flux:heading>
    
    <div class="flex items-center gap-2 bg-sky-100 dark:bg-sky-900/30 px-3 py-1 rounded-full border border-sky-200 dark:border-sky-800">
        <flux:icon.eye class="size-4 text-sky-600 dark:text-sky-400" />
        <span class="text-sm font-bold text-sky-700 dark:text-sky-300">
            {{ number_format($views) }} {{ trans_choice('view|views', $views) }}
        </span>
    </div>
</div>

<flux:heading class="mt-8" size="lg" level="2" icon="document-text">{{ __("Resume Sections") }}</flux:heading>

<div class="mt-6 flex flex-wrap gap-3">
    {{ $cards }}
</div>

<flux:separator class="mt-10" />

<flux:heading class="mt-10" size="lg" level="2" icon="cog-6-tooth">{{ __("Configuration") }}</flux:heading>

<div class="mt-6 flex flex-wrap gap-3">
    {{ $optionsCards }}
</div>

<flux:separator class="mt-10" />

<flux:heading class="mt-10" size="lg" level="2" icon="wrench-screwdriver">{{ __("Maintenance Tools") }}</flux:heading>

<div class="mt-6 flex flex-wrap gap-3">
    {{ $toolsCards }}
</div>