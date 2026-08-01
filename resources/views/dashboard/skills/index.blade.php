<x-layouts::app :title="__('Skills')">

    <flux:heading size="xl" level="1">{{ __("Skills") }}</flux:heading>

    <div class="max-w-xl mt-6">
        <livewire:resume.skills.create-skill />
    </div>

    <livewire:resume.skills.skills-table />

</x-layouts::app>
