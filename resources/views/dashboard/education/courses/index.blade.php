<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Education Courses") }}</flux:heading>

    <div class="mt-6">
        <flux:button variant="primary" size="xs" :href="route('dashboard.education')">Go back to Education</flux:button>
    </div>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.education.courses.create-course :educationId="$education->id" />
    </div>

    <livewire:resume.education.courses.courses-table :educationId="$education->id" />

</x-layouts::app>
