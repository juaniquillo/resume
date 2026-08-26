<x-layouts::app :title="__('Volunteers')">

    <flux:heading size="xl" level="1">{{ __("Volunteers") }}</flux:heading>

     <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit volunteer items at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.volunteers.create-volunteer />
    </div>

    <livewire:resume.volunteers.volunteers-table/>
</x-layouts::app>
