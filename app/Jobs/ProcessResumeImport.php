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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

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

            // Process Basics
            if (isset($data['basics'])) {
                $basicsData = $data['basics'];

                $mappedBasics = BasicsCrud::build()->make()
                    ->execute(new NameValueAction($basicsData))
                    ->toArray();

                (new UpdateBasics($mappedBasics, $user))->handle();

                /** @var Basic|null $basics */
                $basics = $user->basics()->first();

                if ($basics) {
                    if (isset($basicsData['location'])) {
                        $mappedLocation = LocationsCrud::build()->make()
                            ->execute(new NameValueAction($basicsData['location']))
                            ->toArray();

                        (new UpdateLocation($mappedLocation, $basics))->handle();
                    }

                    if (isset($basicsData['profiles'])) {
                        $profileCrud = ProfilesCrud::build()->make();
                        foreach ($basicsData['profiles'] as $profile) {
                            $mappedProfile = $profileCrud->execute(new NameValueAction($profile))
                                ->toArray();

                            (new CreateProfile($mappedProfile, $basics))->handle();
                        }
                    }
                }
            }

            // Process Work
            if (isset($data['work'])) {
                $workCrud = WorksCrud::build()->make();
                foreach ($data['work'] as $workData) {
                    $mapped = $workCrud->execute(new NameValueAction($workData))
                        ->toArray();

                    $work = (new CreateWork($mapped, $user))->handle();
                    if (isset($workData['highlights'])) {
                        foreach ($workData['highlights'] as $highlight) {
                            (new CreateHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $work))->handle();
                        }
                    }
                }
            }

            // Process Volunteer
            if (isset($data['volunteer'])) {
                $volunteerCrud = VolunteersCrud::build()->make();
                foreach ($data['volunteer'] as $volunteerData) {
                    $mapped = $volunteerCrud->execute(new NameValueAction($volunteerData))
                        ->toArray();

                    $volunteer = (new CreateVolunteer($mapped, $user))->handle();
                    if (isset($volunteerData['highlights'])) {
                        foreach ($volunteerData['highlights'] as $highlight) {
                            (new \App\Actions\Resume\Volunteer\CreateHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $volunteer))->handle();
                        }
                    }
                }
            }

            // Process Education
            if (isset($data['education'])) {
                $educationCrud = EducationCrud::build()->make();
                foreach ($data['education'] as $eduData) {
                    $mapped = $educationCrud->execute(new NameValueAction($eduData))
                        ->toArray();

                    (new CreateEducation($mapped, $user))->handle();
                }
            }

            // Process Awards
            if (isset($data['awards'])) {
                $awardCrud = AwardsCrud::build()->make();
                foreach ($data['awards'] as $awardData) {
                    $mapped = $awardCrud->execute(new NameValueAction($awardData))
                        ->toArray();

                    (new CreateAward($mapped, $user))->handle();
                }
            }

            // Process Certificates
            if (isset($data['certificates'])) {
                $certificateCrud = CertificatesCrud::build()->make();
                foreach ($data['certificates'] as $certData) {
                    $mapped = $certificateCrud->execute(new NameValueAction($certData))
                        ->toArray();

                    (new CreateCertificate($mapped, $user))->handle();
                }
            }

            // Process Publications
            if (isset($data['publications'])) {
                $publicationCrud = PublicationsCrud::build()->make();
                foreach ($data['publications'] as $pubData) {
                    $mapped = $publicationCrud->execute(new NameValueAction($pubData))
                        ->toArray();

                    (new CreatePublication($mapped, $user))->handle();
                }
            }

            // Process Skills
            if (isset($data['skills'])) {
                $skillCrud = SkillsCrud::build()->make();
                foreach ($data['skills'] as $skillData) {
                    $mapped = $skillCrud->execute(new NameValueAction($skillData))
                        ->toArray();

                    (new CreateSkill($mapped, $user))->handle();
                }
            }

            // Process Languages
            if (isset($data['languages'])) {
                $languageCrud = LanguagesCrud::build()->make();
                foreach ($data['languages'] as $langData) {
                    $mapped = $languageCrud->execute(new NameValueAction($langData))
                        ->toArray();

                    (new CreateLanguage($mapped, $user))->handle();
                }
            }

            // Process Interests
            if (isset($data['interests'])) {
                $interestCrud = InterestsCrud::build()->make();
                foreach ($data['interests'] as $interestData) {
                    $mapped = $interestCrud->execute(new NameValueAction($interestData))
                        ->toArray();

                    (new CreateInterest($mapped, $user))->handle();
                }
            }

            // Process References
            if (isset($data['references'])) {
                $referenceCrud = ReferencesCrud::build()->make();
                foreach ($data['references'] as $refData) {
                    $mapped = $referenceCrud->execute(new NameValueAction($refData))
                        ->toArray();

                    (new CreateReference($mapped, $user))->handle();
                }
            }

            // Process Projects
            if (isset($data['projects'])) {
                $projectCrud = ProjectsCrud::build()->make();
                foreach ($data['projects'] as $projectData) {
                    $mapped = $projectCrud->execute(new NameValueAction($projectData))
                        ->toArray();

                    $project = (new CreateProject($mapped, $user))->handle();
                    if (isset($projectData['highlights'])) {
                        foreach ($projectData['highlights'] as $highlight) {
                            (new CreateProjectHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $project))->handle();
                        }
                    }
                }
            }

            $this->import->update(['status' => 'completed']);
        } catch (\Exception $e) {
            $this->import->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
