<x-layouts::app :title="__('Dashboard')">
    
    <flux:heading size="xl" level="1">{{ __("Profiles") }}</flux:heading>
    
    <div class="max-w-xl">
        {{ $form }}
    </div>
</x-layouts::app>
