<x-layouts::app :title="__('Education')">

    <flux:heading size="xl" level="1">{{ __("Education") }}</flux:heading>

    <div class="max-w-xl mt-6">
        <livewire:resume.education.create-education />
    </div>

    <livewire:resume.education.education-table />

</x-layouts::app>
