<x-layouts::app :title="__('Volunteers')">

    <flux:heading size="xl" level="1">{{ __("Volunteer Highlights") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" wire:navigate :href="route('dashboard.volunteers')">Go back to Volunteers</flux:button>
    </div>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.volunteers.highlights.create-highlight :volunteer="$volunteer" />
    </div>

    <livewire:resume.volunteers.highlights.volunteers-highlights-table :volunteer="$volunteer" />
    
</x-layouts::app>
