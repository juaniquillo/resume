<flux:heading size="xl" level="1">{{ __("Welcome! Let's get started.") }}</flux:heading>

<flux:subheading class="mt-2 text-lg">{{ __("Choose how you want to build your professional resume.") }}</flux:subheading>

<div class="mt-8 grid gap-6 md:grid-cols-2">
    <a href="{{ route('dashboard.basics') }}" wire:navigate class="group">
        <flux:card class="h-full border-2 border-zinc-200 dark:border-zinc-800 transition-all duration-300 hover:border-sky-500 hover:shadow-lg dark:hover:border-sky-400">
            <div class="flex flex-col items-center p-6 text-center">
                <div class="mb-4 rounded-2xl bg-sky-100 p-5 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400 group-hover:scale-110 transition-transform duration-300">
                    <flux:icon name="document-text" class="size-12" />
                </div>
                <flux:heading size="lg" class="group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">{{ __("Build Manually") }}</flux:heading>
                <flux:text class="mt-3 leading-relaxed">
                    {{ __("Enter your information step-by-step. Start with your contact details, summary, and location to unlock more sections.") }}
                </flux:text>
            </div>
        </flux:card>
    </a>

    <a href="{{ route('dashboard.resume.import') }}" wire:navigate class="group">
        <flux:card class="h-full border-2 border-zinc-200 dark:border-zinc-800 transition-all duration-300 hover:border-emerald-500 hover:shadow-lg dark:hover:border-emerald-400">
            <div class="flex flex-col items-center p-6 text-center">
                <div class="mb-4 rounded-2xl bg-emerald-100 p-5 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                    <flux:icon name="arrow-up-tray" class="size-12" />
                </div>
                <flux:heading size="lg" class="group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ __("Import JSON") }}</flux:heading>
                <flux:text class="mt-3 leading-relaxed">
                    {{ __("Fast-track your setup by importing an existing JSON Resume file. We support the official open standard.") }}
                </flux:text>
            </div>
        </flux:card>
    </a>
</div>

<div class="mt-12 rounded-2xl bg-zinc-50 p-8 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800">
    <div class="flex items-start gap-4">
        <div class="mt-1 rounded-full bg-zinc-200 dark:bg-zinc-800 p-2">
            <flux:icon name="information-circle" class="size-5 text-zinc-500" />
        </div>
        <div>
            <flux:heading size="sm" class="uppercase tracking-wider text-zinc-500 font-bold">{{ __("Pro Tip: Why JSON Import?") }}</flux:heading>
            <flux:text class="mt-2 text-base">
                {{ __("If you already have a resume in the") }} <a href="https://jsonresume.org/" target="_blank" class="font-bold underline text-sky-600 dark:text-sky-400 hover:no-underline">{{ __("JSON Resume standard") }}</a>, {{ __("you can import it here to instantly populate all sections including work experience, education, skills, and more.") }}
            </flux:text>
        </div>
    </div>
</div>