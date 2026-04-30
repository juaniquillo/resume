@php
use App\Components\Nav\DashboardNav;
use App\Components\Nav\ToolsNav;

$cards = DashboardNav::makeCards();
$toolsCards = ToolsNav::makeCards();
@endphp

<x-layouts::app :title="__('Dashboard')">
    
    <div class="max-w-4xl mt-6">
        <flux:heading size="xl" level="1">{{ __("Resume builder") }}</flux:heading>
        <flux:text class="mt-2 mb-6 text-base">{{ __("Choose the builder") }}</flux:text>
        
        <div class="flex flex-wrap gap-3">
            {{ $cards }}
        </div>

        <flux:separator class="mt-6" />

        <div class="mt-6 flex flex-wrap gap-3">
            {{ $toolsCards }}
        </div>

    </div>
        
</x-layouts::app >
