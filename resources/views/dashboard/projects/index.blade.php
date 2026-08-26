<x-layouts::app :title="__('Projects')">

    <flux:heading size="xl" level="1">{{ __("Projects") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit projects at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.projects.create-project />
    </div>

    <livewire:resume.projects.projects-table />

</x-layouts::app>
