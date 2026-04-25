<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{  __("Create Your Location") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" :href="route('dashboard.basics.locations')">Go back</flux:button>
    </div>

    <div class="max-w-xl mt-6">
        @if ($form)
            {{ $form }}
        @else
            <p>{{ __('basics.errors.basics_not_found') }}</p>
        @endif
    </div>
    
    
</x-layouts::app>
