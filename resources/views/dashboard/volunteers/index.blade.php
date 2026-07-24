<x-layouts::app :title="__('Volunteers')">

    <flux:heading size="xl" level="1">{{ __("Volunteers") }}</flux:heading>

     <div class="max-w-xl mt-6">
        <livewire:resume.volunteers.create-volunteer />
    </div>

    <livewire:resume.volunteers.volunteers-table/>
</x-layouts::app>
