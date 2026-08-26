
<x-layouts::app :title="__('Resume Export')">

    <flux:heading size="xl" level="1">{{ __("Resume Export") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("Export your resume in JSON or PDF format. The process will run in the background, and you will be able to download the file once it's completed. You can keep up to :limit exports at a time.", ['limit' => $limit]) }}
        </flux:text>

        <livewire:resume.export.create-resume-export />
    </div>

    <livewire:resume.export.resume-exports-table />

</x-layouts::app>
