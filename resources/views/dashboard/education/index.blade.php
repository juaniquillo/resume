<x-layouts::app :title="__('Education')">

    <flux:heading size="xl" level="1">{{ __("Education") }}</flux:heading>

    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit education items at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.education.create-education />
    </div>

    <livewire:resume.education.education-table />

</x-layouts::app>
