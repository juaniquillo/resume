<x-layouts::app :title="__('Resume Import')">

    <flux:heading size="xl" level="1">{{ __("Resume Import") }}</flux:heading>

    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("Upload your resume in JSON Resume format to automatically populate your profile. You can keep up to five imports at a time.") }}
        </flux:text>
        <flux:text class="mb-4 font-bold" color="orange">
            {{ __('Warning: Importing a new resume will overwrite your existing data.') }}
        </flux:text>

        <livewire:resume.import.create-resume-import />
    </div>

    <livewire:resume.import.resume-imports-table />

</x-layouts::app>
