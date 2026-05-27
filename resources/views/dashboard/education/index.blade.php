<x-layouts::app :title="__('Education')">

    <flux:heading size="xl" level="1">{{ __("Education") }}</flux:heading>

    <div class="max-w-xl mt-6">
        {{ $form }}
    </div>

    @if ($table ?? null)
        <flux:separator variant="subtle" class="mt-6" />
        <x-table-container paginator="{{ $paginator }}">
            {{ $table }}
        </x-table-container>
    @endif
</x-layouts::app>
