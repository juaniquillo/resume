<?php

namespace App\Jobs;

use App\Models\ResumeExport;
use App\Presenters\Cache\ResumePresenterCacheManager;
use App\Presenters\ResumePresenter;
use App\Presenters\Themes\ThemeFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\LaravelPdf\Facades\Pdf;

class ProcessPdfExport implements ShouldQueue
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
    public $timeout = 180;

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
        $this->export->update(['status' => 'processing']);

        try {
            $user = $this->export->user;
            $theme = $this->export->theme
                ? ThemeFactory::make($this->export->theme)
                : ThemeFactory::forUser($user);

            $presenter = new ResumePresenter($user, $theme, isPdf: true);

            $html = view('pages.resume', [
                'user' => $user,
                'theme' => $presenter->getTheme(),
                'resumeComponent' => $presenter->present()->toHtml(),
                'minimalView' => true,
                'showThemeToggle' => false,
            ])->render();

            $filename = "resume-export-{$this->export->id}-".now()->timestamp.'.pdf';
            $path = "exports/resumes/{$filename}";

            Pdf::html($html)
                ->disk(config('filesystems.default'))
                ->save($path);

            $this->export->update([
                'status' => 'completed',
                'file_path' => $path,
            ]);

            if ($this->export->allow_download) {
                $manager = new ResumePresenterCacheManager($user);
                $manager->clearCache();
            }

        } catch (\Exception $e) {
            $this->export->update([
                'status' => 'failed',
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
            'status' => 'failed',
            'error' => $exception->getMessage(),
        ]);
    }
}
