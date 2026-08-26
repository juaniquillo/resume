<x-layouts::app :title="__('Volunteers')">

    <flux:heading size="xl" level="1">{{ __("Volunteer Highlights") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" wire:navigate :href="route('dashboard.volunteers')">Go back to Volunteers</flux:button>
    </div>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit highlights at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.highlights.create-highlight :model="$volunteer" />
    </div>

    <livewire:resume.highlights.highlight-table :model="$volunteer" />
    
</x-layouts::app>
