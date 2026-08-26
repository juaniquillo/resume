<x-layouts::app :title="__('Profiles')">

    <flux:heading size="xl" level="1">{{ __("Create Your Profile") }}</flux:heading>

    @if ($basics)
        <div class="max-w-xl mt-6">
            <flux:text class="mb-4">
                {{ __("You can keep up to :limit profiles at a time.", ['limit' => $limit]) }}
            </flux:text>
            <livewire:resume.profiles.create-profile />
        </div>

        <livewire:resume.profiles.profiles-table />
    @else
        <p class="mt-6">{{ __('basics.errors.basics_not_found') }}</p>
    @endif

</x-layouts::app>
