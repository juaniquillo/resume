<x-layouts::app :title="__('Dashboard')">
    
    <flux:heading size="xl" level="1">{{ __("Works") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        {{ $form }}
    </div>

    <div class="mt-6">
        {{ $table }}
    </div>
</x-layouts::app>
