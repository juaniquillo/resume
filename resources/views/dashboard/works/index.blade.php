<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Works") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        {{ $form }}
    </div>

    <flux:separator variant="subtle" class="mt-6" />

    @if ($table ?? null)
        <div class="px-5 py-2 border border-gray-700 rounded-lg mt-6">
            {{ $table }}
        </div>
    @endif
</x-layouts::app>
