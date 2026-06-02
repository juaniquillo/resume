@php
    use App\Components\Nav\ResumeNav;
    use App\Components\Nav\ResumeOptionNav;
    use App\Components\Nav\ToolsNav;

    $cards = ResumeNav::makeCards();
    $toolsCards = ToolsNav::makeCards();
    $optionsCards = ResumeOptionNav::makeCards();

@endphp

<x-layouts::app :title="__('Dashboard')">
    
    <div class="max-w-4xl">
        @if (! $hasBasics)
            <div class="mb-6 text-sm">
                <x-alerts ignore-session warning="{{ __('Your basic information is missing. Please fill it out to start building your resume.') }}" />
            </div>
        @endif


        <div class="flex items-center justify-between">
            <flux:heading size="xl" level="1">{{ __("Resume builder") }}</flux:heading>
            
            <div class="flex items-center gap-2 bg-sky-100 dark:bg-sky-900/30 px-3 py-1 rounded-full border border-sky-200 dark:border-sky-800">
                <flux:icon.eye class="size-4 text-sky-600 dark:text-sky-400" />
                <span class="text-sm font-bold text-sky-700 dark:text-sky-300">
                    {{ number_format($views) }} {{ trans_choice('view|views', $views) }}
                </span>
            </div>
        </div>

        <flux:heading class="mt-6" size="xl" level="1">{{ __("Resume") }}</flux:heading>

        <div class="mt-6 flex flex-wrap gap-3">
            {{ $cards }}
        </div>

        <flux:separator class="mt-6" />

        <flux:heading class="mt-6" size="xl" level="1">{{ __("Options") }}</flux:heading>

        <div class="mt-6 flex flex-wrap gap-3">
            {{ $optionsCards }}
        </div>
        
        <flux:separator class="mt-6" />

        <flux:heading class="mt-6" size="xl" level="1">{{ __("Tools") }}</flux:heading>

        <div class="mt-6 flex flex-wrap gap-3">
            {{ $toolsCards }}
        </div>
        
    </div>
        
</x-layouts::app >
