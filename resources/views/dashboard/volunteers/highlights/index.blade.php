<x-layouts::app :title="__('Volunteers')">

    <flux:heading size="xl" level="1">{{ __("Volunteer Highlights") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" wire:navigate :href="route('dashboard.volunteers')">Go back to Volunteers</flux:button>
    </div>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.highlights.create-highlight :model="$volunteer" />
    </div>

    <livewire:resume.highlights.highlight-table :model="$volunteer" />
    
</x-layouts::app>
