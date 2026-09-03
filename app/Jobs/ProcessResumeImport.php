<?php

namespace App\Jobs;

use App\Enums\ProcessStatus;
use App\Models\ResumeImport;
use App\Models\User;
use App\Presenters\Resume\ResumeDataLoader;
use App\Services\ResumeImport\Processors\AwardsProcessor;
use App\Services\ResumeImport\Processors\BasicsProcessor;
use App\Services\ResumeImport\Processors\CertificatesProcessor;
use App\Services\ResumeImport\Processors\EducationProcessor;
use App\Services\ResumeImport\Processors\InterestsProcessor;
use App\Services\ResumeImport\Processors\LanguagesProcessor;
use App\Services\ResumeImport\Processors\ProjectsProcessor;
use App\Services\ResumeImport\Processors\PublicationsProcessor;
use App\Services\ResumeImport\Processors\ReferencesProcessor;
use App\Services\ResumeImport\Processors\SkillsProcessor;
use App\Services\ResumeImport\Processors\VolunteerProcessor;
use App\Services\ResumeImport\Processors\WorkProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcessResumeImport implements ShouldQueue
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
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ResumeImport $import
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        resolve(ResumeDataLoader::class)->clearCache($this->import->user_id);

        $this->import->update(['status' => ProcessStatus::PROCESSING]);

        try {
            $json = Storage::get($this->import->file_path);
            if (! $json) {
                throw new \Exception('File not found or empty.');
            }
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            /** @var User $user */
            $user = $this->import->user;

            DB::transaction(function () use ($user, $data) {
                (new BasicsProcessor)->process($user, $data);
                (new WorkProcessor)->process($user, $data);
                (new VolunteerProcessor)->process($user, $data);
                (new EducationProcessor)->process($user, $data);
                (new AwardsProcessor)->process($user, $data);
                (new CertificatesProcessor)->process($user, $data);
                (new PublicationsProcessor)->process($user, $data);
                (new SkillsProcessor)->process($user, $data);
                (new LanguagesProcessor)->process($user, $data);
                (new InterestsProcessor)->process($user, $data);
                (new ReferencesProcessor)->process($user, $data);
                (new ProjectsProcessor)->process($user, $data);
            });

            $this->import->update(['status' => ProcessStatus::COMPLETED]);
        } catch (\Exception $e) {
            $this->import->update([
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
        $this->import->update([
            'status' => ProcessStatus::FAILED,
            'error' => $exception->getMessage(),
        ]);
    }
}
