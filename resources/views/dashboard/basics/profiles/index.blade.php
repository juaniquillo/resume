<x-layouts::app :title="__('Dashboard')">
    
    <flux:heading size="xl" level="1">{{ __("Profiles") }}</flux:heading>
    
    <div class="max-w-xl mt-6 flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{ $form }}
    </div>
</x-layouts::app>
