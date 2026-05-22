@php
use App\Components\Nav\DashboardNav;
use App\Components\Nav\ResumeOptionNav;
use App\Components\Nav\ToolsNav;

$cards = DashboardNav::makeCards();
$toolsCards = ToolsNav::makeCards();
$optionsCards = ResumeOptionNav::makeCards();
@endphp

<x-layouts::app :title="__('Dashboard')">
    
    <div class="max-w-4xl">
        <flux:heading size="xl" level="1">{{ __("Resume builder") }}</flux:heading>
        
        <div class="mt-6 flex flex-wrap gap-3">
            {{ $cards }}
        </div>

        <flux:separator class="mt-6" />

        <flux:heading class="mt-6" size="xl" level="1">{{ __("Tools") }}</flux:heading>

        <div class="mt-6 flex flex-wrap gap-3">
            {{ $toolsCards }}
        </div>
        <flux:separator class="mt-6" />

        <flux:heading class="mt-6" size="xl" level="1">{{ __("Options") }}</flux:heading>

        <div class="mt-6 flex flex-wrap gap-3">
            {{ $optionsCards }}
        </div>
    </div>
        
</x-layouts::app >
