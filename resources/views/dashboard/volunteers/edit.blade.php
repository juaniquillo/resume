<x-layouts::app :title="__('Volunteers')">

    <flux:heading size="xl" level="1">{{ __("Edit Volunteer") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" :href="route('dashboard.volunteers')">Go back</flux:button>
    </div>
    
    <div class="max-w-xl mt-4">
        {{ $form }}
    </div>

</x-layouts::app>
