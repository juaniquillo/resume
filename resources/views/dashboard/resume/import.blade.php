<x-layouts::app :title="__('Resume Import')">

    <flux:heading size="xl" level="1">{{ __("Resume Import") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <div class="mb-4 text-sm">
            <x-alerts warning="{{ __('Warning: Importing a new resume will overwrite your existing data.') }}" />
        </div>

        <flux:text class="mb-4">
            {{ __("Upload your resume in JSON Resume format to automatically populate your profile.") }}
        </flux:text>

        {{ $form }}
    </div>

    @if ($table ?? null)
        <flux:separator variant="subtle" class="mt-6" />

        <x-table-container paginator="{{ $paginator }}">
            {{ $table }}
        </x-table-container>
    @endif
</x-layouts::app>
