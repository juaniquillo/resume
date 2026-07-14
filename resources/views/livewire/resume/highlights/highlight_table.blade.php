<div>
    @if ($table)

        <flux:separator variant="subtle" class="mt-6" />
        
        <x-table-container>
            {{ $table }}
        </x-table-container>
    @endif
</div>
