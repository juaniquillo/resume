<x-layouts::app :title="__('Education')">
    
    <flux:heading size="xl" level="1">{{ __("Education") }}</flux:heading>
    
    <div class="max-w-xl mt-6 flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{ $form }}
    </div>
</x-layouts::app>
