<x-layouts::app :title="__('Projects')">

    <flux:heading size="xl" level="1">{{ __("Projects") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.projects.create-project />
    </div>

    <livewire:resume.projects.projects-table />

</x-layouts::app>
