<?php

namespace App\Jobs;

use App\Actions\Resume\Award\CreateAward;
use App\Actions\Resume\Basics\CreateProfile;
use App\Actions\Resume\Basics\UpdateBasics;
use App\Actions\Resume\Basics\UpdateLocation;
use App\Actions\Resume\Certificate\CreateCertificate;
use App\Actions\Resume\Education\CreateEducation;
use App\Actions\Resume\Interest\CreateInterest;
use App\Actions\Resume\Language\CreateLanguage;
use App\Actions\Resume\Project\CreateHighlight as CreateProjectHighlight;
use App\Actions\Resume\Project\CreateProject;
use App\Actions\Resume\Publication\CreatePublication;
use App\Actions\Resume\Reference\CreateReference;
use App\Actions\Resume\Skill\CreateSkill;
use App\Actions\Resume\Volunteer\CreateVolunteer;
use App\Actions\Resume\Work\CreateHighlight;
use App\Actions\Resume\Work\CreateWork;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Squema\Awards\AwardsCrud;
use App\Cruds\Squema\Basics\BasicsCrud;
use App\Cruds\Squema\Certificates\CertificatesCrud;
use App\Cruds\Squema\Education\EducationCrud;
use App\Cruds\Squema\Interests\InterestsCrud;
use App\Cruds\Squema\Languages\LanguagesCrud;
use App\Cruds\Squema\Locations\LocationsCrud;
use App\Cruds\Squema\Profiles\ProfilesCrud;
use App\Cruds\Squema\Projects\ProjectsCrud;
use App\Cruds\Squema\Publications\PublicationsCrud;
use App\Cruds\Squema\References\ReferencesCrud;
use App\Cruds\Squema\Skills\SkillsCrud;
use App\Cruds\Squema\Volunteers\VolunteersCrud;
use App\Cruds\Squema\Works\WorksCrud;
use App\Models\Basic;
use App\Models\ResumeImport;
use App\Models\User;
use App\Support\RequestUtils;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcessResumeImport implements ShouldQueue
{
    use Queueable;

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
        $this->import->update(['status' => 'processing']);

        try {
            $json = Storage::get($this->import->file_path);
            if (! $json) {
                throw new \Exception('File not found or empty.');
            }
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            /** @var User $user */
            $user = $this->import->user;

            $this->processBasics($user, $data);
            $this->processWork($user, $data);
            $this->processVolunteer($user, $data);
            $this->processEducation($user, $data);
            $this->processAwards($user, $data);
            $this->processCertificates($user, $data);
            $this->processPublications($user, $data);
            $this->processSkills($user, $data);
            $this->processLanguages($user, $data);
            $this->processInterests($user, $data);
            $this->processReferences($user, $data);
            $this->processProjects($user, $data);

            $this->import->update(['status' => 'completed']);
        } catch (\Exception $e) {
            $this->import->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function validate(array $data, array $rules): array
    {
        return Validator::make($data, $rules)->validate();
    }

    private function processBasics(User $user, array $data): void
    {
        if (! isset($data['basics'])) {
            return;
        }

        $basicsData = $data['basics'];
        $crud = BasicsCrud::build();
        $inputs = $crud->make();

        $mappedBasics = $inputs->execute(new NameValueAction($basicsData))
            ->toArray();

        $rules = $inputs->execute(new LaravelValidationRulesAction)->toArray();
        $validated = $this->validate($mappedBasics, $rules);

        (new UpdateBasics($validated, $user))->handle();

        /** @var Basic|null $basics */
        $basics = $user->basics()->first();

        if ($basics) {
            if (isset($basicsData['location'])) {
                $locationCrud = LocationsCrud::build();
                $locationInputs = $locationCrud->make();

                $mappedLocation = $locationInputs->execute(new NameValueAction($basicsData['location']))
                    ->toArray();

                $locationRules = $locationInputs->execute(new LaravelValidationRulesAction)->toArray();
                $validatedLocation = $this->validate($mappedLocation, $locationRules);

                (new UpdateLocation($validatedLocation, $basics))->handle();
            }

            if (isset($basicsData['profiles'])) {
                $profileCrud = ProfilesCrud::build();
                $profileInputs = $profileCrud->make();
                $profileRules = $profileInputs->execute(new LaravelValidationRulesAction)->toArray();

                foreach ($basicsData['profiles'] as $profile) {
                    $mappedProfile = $profileInputs->execute(new NameValueAction($profile))
                        ->toArray();

                    $validatedProfile = $this->validate($mappedProfile, $profileRules);

                    (new CreateProfile($validatedProfile, $basics))->handle();
                }
            }
        }
    }

    private function processWork(User $user, array $data): void
    {
        if (! isset($data['work'])) {
            return;
        }

        $workCrud = WorksCrud::build();
        $workInputs = $workCrud->make();
        $workRules = $workInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['work'] as $workData) {
            $mapped = $workInputs->execute(new NameValueAction($workData))
                ->toArray();

            $validated = $this->validate($mapped, $workRules);

            $work = (new CreateWork($validated, $user))->handle();
            if (isset($workData['highlights'])) {
                foreach ($workData['highlights'] as $highlight) {
                    (new CreateHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $work))->handle();
                }
            }
        }
    }

    private function processVolunteer(User $user, array $data): void
    {
        if (! isset($data['volunteer'])) {
            return;
        }

        $volunteerCrud = VolunteersCrud::build();
        $volunteerInputs = $volunteerCrud->make();
        $volunteerRules = $volunteerInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['volunteer'] as $volunteerData) {
            $mapped = $volunteerInputs->execute(new NameValueAction($volunteerData))
                ->toArray();

            $validated = $this->validate($mapped, $volunteerRules);

            $volunteer = (new CreateVolunteer($validated, $user))->handle();
            if (isset($volunteerData['highlights'])) {
                foreach ($volunteerData['highlights'] as $highlight) {
                    (new \App\Actions\Resume\Volunteer\CreateHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $volunteer))->handle();
                }
            }
        }
    }

    private function processEducation(User $user, array $data): void
    {
        if (! isset($data['education'])) {
            return;
        }

        $educationCrud = EducationCrud::build();
        $educationInputs = $educationCrud->make();
        $educationRules = $educationInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['education'] as $eduData) {
            $mapped = $educationInputs->execute(new NameValueAction($eduData))
                ->toArray();

            $validated = $this->validate($mapped, $educationRules);

            (new CreateEducation($validated, $user))->handle();
        }
    }

    private function processAwards(User $user, array $data): void
    {
        if (! isset($data['awards'])) {
            return;
        }

        $awardCrud = AwardsCrud::build();
        $awardInputs = $awardCrud->make();
        $awardRules = $awardInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['awards'] as $awardData) {
            $mapped = $awardInputs->execute(new NameValueAction($awardData))
                ->toArray();

            $validated = $this->validate($mapped, $awardRules);

            (new CreateAward($validated, $user))->handle();
        }
    }

    private function processCertificates(User $user, array $data): void
    {
        if (! isset($data['certificates'])) {
            return;
        }

        $certificateCrud = CertificatesCrud::build();
        $certificateInputs = $certificateCrud->make();
        $certificateRules = $certificateInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['certificates'] as $certData) {
            $mapped = $certificateInputs->execute(new NameValueAction($certData))
                ->toArray();

            $validated = $this->validate($mapped, $certificateRules);

            (new CreateCertificate($validated, $user))->handle();
        }
    }

    private function processPublications(User $user, array $data): void
    {
        if (! isset($data['publications'])) {
            return;
        }

        $publicationCrud = PublicationsCrud::build();
        $publicationInputs = $publicationCrud->make();
        $publicationRules = $publicationInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['publications'] as $pubData) {
            $mapped = $publicationInputs->execute(new NameValueAction($pubData))
                ->toArray();

            $validated = $this->validate($mapped, $publicationRules);

            (new CreatePublication($validated, $user))->handle();
        }
    }

    private function processSkills(User $user, array $data): void
    {
        if (! isset($data['skills'])) {
            return;
        }

        $skillCrud = SkillsCrud::build();
        $skillInputs = $skillCrud->make();
        $skillRules = $skillInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['skills'] as $skillData) {
            $mapped = $skillInputs->execute(new NameValueAction($skillData))
                ->toArray();

            if (isset($mapped['keywords'])) {
                $mapped['keywords'] = RequestUtils::commaSeparatedToArray($mapped['keywords']);
            }

            $validated = $this->validate($mapped, $skillRules);

            (new CreateSkill($validated, $user))->handle();
        }
    }

    private function processLanguages(User $user, array $data): void
    {
        if (! isset($data['languages'])) {
            return;
        }

        $languageCrud = LanguagesCrud::build();
        $languageInputs = $languageCrud->make();
        $languageRules = $languageInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['languages'] as $langData) {
            $mapped = $languageInputs->execute(new NameValueAction($langData))
                ->toArray();

            $validated = $this->validate($mapped, $languageRules);

            (new CreateLanguage($validated, $user))->handle();
        }
    }

    private function processInterests(User $user, array $data): void
    {
        if (! isset($data['interests'])) {
            return;
        }

        $interestCrud = InterestsCrud::build();
        $interestInputs = $interestCrud->make();
        $interestRules = $interestInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['interests'] as $interestData) {
            $mapped = $interestInputs->execute(new NameValueAction($interestData))
                ->toArray();

            if (isset($mapped['keywords'])) {
                $mapped['keywords'] = RequestUtils::commaSeparatedToArray($mapped['keywords']);
            }

            $validated = $this->validate($mapped, $interestRules);

            (new CreateInterest($validated, $user))->handle();
        }
    }

    private function processReferences(User $user, array $data): void
    {
        if (! isset($data['references'])) {
            return;
        }

        $referenceCrud = ReferencesCrud::build();
        $referenceInputs = $referenceCrud->make();
        $referenceRules = $referenceInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['references'] as $refData) {
            $mapped = $referenceInputs->execute(new NameValueAction($refData))
                ->toArray();

            if (isset($mapped['keywords'])) {
                $mapped['keywords'] = RequestUtils::commaSeparatedToArray($mapped['keywords']);
            }

            $validated = $this->validate($mapped, $referenceRules);

            (new CreateReference($validated, $user))->handle();
        }
    }

    private function processProjects(User $user, array $data): void
    {
        if (! isset($data['projects'])) {
            return;
        }

        $projectCrud = ProjectsCrud::build();
        $projectInputs = $projectCrud->make();
        $projectRules = $projectInputs->execute(new LaravelValidationRulesAction)->toArray();

        foreach ($data['projects'] as $projectData) {
            $mapped = $projectInputs->execute(new NameValueAction($projectData))
                ->toArray();

            $validated = $this->validate($mapped, $projectRules);

            $project = (new CreateProject($validated, $user))->handle();
            if (isset($projectData['highlights'])) {
                foreach ($projectData['highlights'] as $highlight) {
                    (new CreateProjectHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $project))->handle();
                }
            }
        }
    }
}
