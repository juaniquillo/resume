<x-layouts::app :title="__('Edit Award')">
    
    <flux:heading size="xl" level="1">{{ __("Awards") }}</flux:heading>

     <div class="mt-6">
        <flux:button variant="primary" size="xs" :href="route('dashboard.awards')">Go back</flux:button>
    </div>
    
    <div class="max-w-xl mt-6">
        {{ $form }}
    </div>
</x-layouts::app>
