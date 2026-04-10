@php
    use App\Components\Nav\DashboardNav;
    $cards = DashboardNav::makeCards();
@endphp

<x-layouts::app :title="__('Dashboard')">
    
    <div class="max-w-4xl mt-6">
        <flux:heading size="xl" level="1">{{ __("Resume builder") }}</flux:heading>
        <flux:text class="mt-2 mb-6 text-base">{{ __("Choose the builder") }}</flux:text>
        
        <div class="flex flex-wrap gap-3">
            {{ $cards }}
        </div>
    </div>
        
</x-layouts::app>
