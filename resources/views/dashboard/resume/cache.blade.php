<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Clear Cache") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        
        <flux:text class="mb-4">
            {{ __("Force clear the resume cache.") }}
        </flux:text>

        {{ $form }}
    </div>
    
</x-layouts::app>
