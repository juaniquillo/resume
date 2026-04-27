<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Edit Project") }}</flux:heading>
    
    <div class="mt-6">
        <flux:button variant="primary" size="xs" :href="route('dashboard.projects')">Go back</flux:button>
    </div>

    <div class="max-w-xl mt-4">
        {{ $form }}
    </div>

</x-layouts::app>
