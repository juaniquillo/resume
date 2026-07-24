<?php

namespace App\Jobs;

use App\Enums\ProcessStatus;
use App\Models\CoverLetter;
use App\Models\ResumeExport;
use App\Presenters\Resume\CoverLetterPresenter;
use App\Presenters\Themes\ThemeFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\LaravelPdf\Facades\Pdf;

class ProcessCoverLetterPdfExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ResumeExport $export) {}

    public function handle(): void
    {
        $this->export->update(['status' => ProcessStatus::PROCESSING]);

        try {
            $user = $this->export->user;
            /** @var CoverLetter|null $coverLetterModel */
            $coverLetterModel = $user->coverLetters()->first();

            if (! $coverLetterModel instanceof CoverLetter) {
                throw new \Exception('Cover letter not found');
            }

            $theme = $this->export->theme
                ? ThemeFactory::make($this->export->theme)
                : ThemeFactory::forUser($user);

            $coverLetter = (new CoverLetterPresenter($coverLetterModel, $theme))->present();

            $html = view('pages.cover-letter-pdf-container', [
                'coverLetter' => $coverLetter,
                'theme' => $theme,
            ])->render();

            // $filename = 'exports/'.str_replace(' ', '-', strtolower($user->name)).'-cover-letter-'.$this->export->uuid.'.pdf';
            // $path = storage_path('app/private/'.$filename);

            $filename = "resume-export-cover-letter-{$this->export->id}-".now()->timestamp.'.pdf';
            $path = "exports/cover-letters/{$filename}";

            Pdf::html($html)
                ->margins(0, 0, 0, 0, 'mm')
                ->disk(config('filesystems.default'))
                ->save($path);

            $this->export->update([
                'status' => ProcessStatus::COMPLETED,
                'file_path' => $path,
            ]);
        } catch (\Throwable $e) {
            $this->export->update([
                'status' => ProcessStatus::FAILED,
                'error' => $e->getMessage(),
            ]);
        }
    }
}



