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

        <div class="relative">
            {{ $form }}

            <div wire:loading wire:target="resetResume" class="absolute inset-0 bg-white/50 dark:bg-zinc-900/50 flex items-center justify-center rounded-lg">
                <flux:spacer />
                <div class="flex items-center gap-3 px-4 py-2 bg-white dark:bg-zinc-800 shadow-lg rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <flux:icon.arrow-path class="animate-spin size-5 text-red-600" />
                    <flux:text class="font-medium text-red-600">{{ __("Resetting your resume... please wait.") }}</flux:text>
                </div>
                <flux:spacer />
            </div>
        </div>
    </div>
</div>
