<?php

namespace App\Jobs;

use App\Actions\Resume\Export\BuildResumeArray;
use App\Models\ResumeExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use JustSteveKing\Resume\Factories\ResumeFactory;

class ProcessResumeExport implements ShouldQueue
{
    use Queueable;

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
            $data = (new BuildResumeArray($user))->handle();

            $resume = ResumeFactory::fromArray($data);
            $json = json_encode($resume, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            $filename = "resume-export-{$this->export->id}-".now()->timestamp.'.json';
            $path = "exports/resumes/{$filename}";

            Storage::put($path, $json);

            $this->export->update([
                'status' => 'completed',
                'file_path' => $path,
            ]);
        } catch (\Exception $e) {
            $this->export->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
