<x-layouts::app :title="__('Resume Export')">

    <flux:heading size="xl" level="1">{{ __("Resume Export") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("Export your resume in JSON format. The process will run in the background, and you will be able to download the file once it's completed.") }}
        </flux:text>

        <form action="{{ route('dashboard.resume.export.store') }}" method="POST">
            @csrf
            {{ $form }}
        </form>
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
