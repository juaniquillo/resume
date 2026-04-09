@php
    use App\Components\Nav\DashboardNav;
    $cards = DashboardNav::makeCards();
@endphp

<x-layouts::app :title="__('Dashboard')">
    
    <flux:heading size="xl" level="1">{{ __("Resume builder") }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">{{ __("Choose the builder") }}</flux:text>

    <div class="flex flex-wrap gap-3">
        {{-- <a href="{{ route('dashboard.basics') }}" aria-label="Basic information">
            <flux:card size="sm" class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                <flux:heading class="flex items-center gap-2">
                    <flux:icon name="document-text" class="text-zinc-400" variant="micro" /> 
                    Basic information
                </flux:heading>
                <flux:text class="mt-2">Update basic info.</flux:text>
            </flux:card>
        </a>
        <a href="{{ route('dashboard.basics') }}" aria-label="Basic information">
            <flux:card size="sm" class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                <flux:heading class="flex items-center gap-2">
                    <flux:icon name="document-text" class="text-zinc-400" variant="micro" /> 
                    Works
                </flux:heading>
                <flux:text class="mt-2">Add, edit and manage your works..</flux:text>
            </flux:card>
        </a> --}}
        {{ $cards }}
    </div>
        
</x-layouts::app>
