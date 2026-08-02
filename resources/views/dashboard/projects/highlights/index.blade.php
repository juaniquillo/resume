<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Project Highlights") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" :href="route('dashboard.projects')" wire:navigate >Go back to Projects</flux:button>
    </div>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.highlights.create-highlight :model="$model" />
    </div>

    <livewire:resume.highlights.highlight-table :model="$model" />
    

</x-layouts::app>
