<x-layouts::app :title="__('Awards')">
    
    <flux:heading size="xl" level="1">{{ __("Awards") }}</flux:heading>
    
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
    
</x-layouts::app>
