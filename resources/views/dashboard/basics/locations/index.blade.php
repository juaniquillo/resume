<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{  __("Create Your Location") }}</flux:heading>

    @if ($basics)
        
        <div class="max-w-xl mt-6">
            {{ $form }}
        </div>
        
        <flux:separator variant="subtle" class="mt-6" />

        @if ($table ?? null)
            <div class="px-5 py-2 bg-gray-200 dark:bg-back-table border border-gray-300 dark:border-slate-700 rounded-lg mt-6 shadow">
                {{ $table }}

                <div class="py-2">
                    {{ $paginator->links() }}
                </div>
            </div>
        @endif

    @else
        <p class="mt-6">{{ __('basics.errors.basics_not_found') }}</p>
    @endif
    
</x-layouts::app>
