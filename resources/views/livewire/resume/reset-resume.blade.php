<div class="max-w-xl">
    <flux:heading size="xl" level="1">{{ __("Reset Resume") }}</flux:heading>
    
    <div class="mt-6">
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
            <div class="flex items-start gap-3">
                <flux:icon.exclamation-triangle class="size-6 text-red-600 dark:text-red-500 shrink-0 mt-0.5" />
                <div class="flex flex-col gap-1">
                    <flux:heading level="3" size="lg" class="text-red-800 dark:text-red-500 font-bold">
                        {{ __("DANGER ZONE: Permanent Data Loss") }}
                    </flux:heading>
                    <flux:text class="text-red-700 dark:text-red-400">
                        {{ __("This action will permanently delete all your resume data, including basics, work experience, education, projects, and everything else you've added. This cannot be undone.") }}
                    </flux:text>
                </div>
            </div>
        </div>

        <div>
            {{ $form }}
        </div>
    </div>
</div>
