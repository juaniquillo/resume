<div>
    <flux:heading size="xl" level="1">{{ __("Edit Work Experience") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" :href="route('dashboard.works')" wire:navigate>Go back</flux:button>
    </div>

    <div class="max-w-xl mt-6">
        {{ $form }}
    </div>

</div>