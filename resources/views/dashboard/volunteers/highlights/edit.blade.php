<x-layouts::app :title="__('Volunteers')">

    <flux:heading size="xl" level="1">{{ __("Edit Volunteer Highlight") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" :href="route('dashboard.volunteers.highlights', $highlight->highlightable_id)">Go back</flux:button>
    </div>
    
    <div class="max-w-xl mt-4">
        <livewire:resume.volunteers.highlights.edit-highlight :highlight="$highlight" />
    </div>

</x-layouts::app>
