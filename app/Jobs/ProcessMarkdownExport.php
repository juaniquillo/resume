<?php

namespace App\Jobs;

use App\Actions\Resume\Export\BuildResumeArray;
use App\Actions\Resume\Export\Markdown\ResumeMarkdownRenderer;
use App\Cruds\Schema\Options\Inputs\ThemeSelectFactory;
use App\Enums\ProcessStatus;
use App\Enums\ResumeSection;
use App\Models\GeneralOption;
use App\Models\ResumeExport;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use JustSteveKing\Resume\Factories\ResumeFactory;

class ProcessMarkdownExport implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ResumeExport $export
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->export->update(['status' => ProcessStatus::PROCESSING]);

        try {
            $user = $this->export->user;
            $customOptions = null;
            if (! empty($this->export->custom_options)) {
                $customOptions = new GeneralOption([
                    ThemeSelectFactory::NAME => $this->export->theme,
                    ...$this->export->custom_options,
                ]);
                $customOptions->setAttribute('user_id', $user->id);
            }

            $resume = (new BuildResumeArray($user, $customOptions))->handle();

            ResumeFactory::fromArray($resume)->validate();

            $resume = $this->applyVisibility($user, $resume);
            $orderedKeys = $this->orderedSectionKeys($user);

            $markdown = (new ResumeMarkdownRenderer)->render($resume, $orderedKeys);

            $filename = "resume-export-{$this->export->uuid}-".now()->timestamp.'.md';
            $path = "exports/resumes/{$filename}";

            Storage::put($path, $markdown);

            $this->export->update([
                'status' => ProcessStatus::COMPLETED,
                'file_path' => $path,
            ]);
        } catch (\Exception $e) {
            $this->export->update([
                'status' => ProcessStatus::FAILED,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->export->update([
            'status' => ProcessStatus::FAILED,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * Remove sections the user hid from the resume payload.
     *
     * @param  array<string, mixed>  $resume
     * @return array<string, mixed>
     */
    private function applyVisibility(User $user, array $resume): array
    {
        /** @var array<string, bool> $settings */
        $settings = (array) ($user->sectionVisibility->settings ?? []);

        if ($settings === []) {
            return $resume;
        }

        /** @var array<string, string> $keyMap ResumeSection value → export key. */
        $keyMap = [];
        foreach (ResumeSection::cases() as $case) {
            if ($case === ResumeSection::DOWNLOADS || $case === ResumeSection::SUMMARY) {
                continue;
            }

            $keyMap[$case->value] = $case->value;
        }

        if (! empty($settings['summary'])) {
            unset($resume['basics']['summary']);
        }

        foreach ($keyMap as $section => $key) {
            if (! empty($settings[$section])) {
                unset($resume[$key]);
            }
        }

        return $resume;
    }

    /**
     * Section keys in the user's display order (basics excluded: always first).
     *
     * @return list<string>
     */
    private function orderedSectionKeys(User $user): array
    {
        $ordered = $user->sectionOrders()
            ->orderBy('sort_order')
            ->pluck('section')
            ->toArray();

        if ($ordered === []) {
            $ordered = collect(ResumeSection::cases())->pluck('value')->toArray();
        }

        return array_values(array_filter(
            $ordered,
            fn (string $section) => $section !== ResumeSection::DOWNLOADS->value && $section !== 'basics',
        ));
    }
}
