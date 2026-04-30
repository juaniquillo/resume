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
use App\Actions\Resume\Project\CreateProject;
use App\Actions\Resume\Publication\CreatePublication;
use App\Actions\Resume\Reference\CreateReference;
use App\Actions\Resume\Skill\CreateSkill;
use App\Actions\Resume\Volunteer\CreateVolunteer;
use App\Actions\Resume\Work\CreateHighlight;
use App\Actions\Resume\Work\CreateWork;
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

                $mappedBasics = [
                    'name' => $basicsData['name'] ?? '',
                    'label' => $basicsData['label'] ?? '',
                    'email' => $basicsData['email'] ?? '',
                    'phone' => $basicsData['phone'] ?? '',
                    'url' => $basicsData['url'] ?? $basicsData['website'] ?? '',
                    'summary' => $basicsData['summary'] ?? '',
                ];

                (new UpdateBasics($mappedBasics, $user))->handle();

                /** @var Basic|null $basics */
                $basics = $user->basics()->first();

                if ($basics) {

                    if (isset($basicsData['location'])) {
                        (new UpdateLocation($basicsData['location'], $basics))->handle();
                    }

                    if (isset($basicsData['profiles'])) {
                        foreach ($basicsData['profiles'] as $profile) {
                            (new CreateProfile($profile, $basics))->handle();
                        }
                    }
                }
            }

            // Process Work
            if (isset($data['work'])) {
                foreach ($data['work'] as $workData) {
                    $mapped = [
                        'name' => $workData['name'] ?? $workData['company'] ?? '',
                        'position' => $workData['position'] ?? '',
                        'starts_at' => $workData['starts_at'] ?? $workData['startDate'] ?? null,
                        'ends_at' => $workData['ends_at'] ?? $workData['endDate'] ?? null,
                        'summary' => $workData['summary'] ?? '',
                    ];

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
                foreach ($data['volunteer'] as $volunteerData) {
                    $mapped = [
                        'organization' => $volunteerData['organization'] ?? '',
                        'position' => $volunteerData['position'] ?? '',
                        'url' => $volunteerData['url'] ?? $volunteerData['website'] ?? null,
                        'starts_at' => $volunteerData['starts_at'] ?? $volunteerData['startDate'] ?? null,
                        'ends_at' => $volunteerData['ends_at'] ?? $volunteerData['endDate'] ?? null,
                        'summary' => $volunteerData['summary'] ?? '',
                    ];

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
                foreach ($data['education'] as $eduData) {
                    $mapped = [
                        'institution' => $eduData['institution'] ?? '',
                        'url' => $eduData['url'] ?? $eduData['website'] ?? null,
                        'area' => $eduData['area'] ?? '',
                        'study_type' => $eduData['study_type'] ?? $eduData['studyType'] ?? '',
                        'score' => $eduData['score'] ?? '',
                        'starts_at' => $eduData['starts_at'] ?? $eduData['startDate'] ?? null,
                        'ends_at' => $eduData['ends_at'] ?? $eduData['endDate'] ?? null,
                    ];
                    (new CreateEducation($mapped, $user))->handle();
                }
            }

            // Process Awards
            if (isset($data['awards'])) {
                foreach ($data['awards'] as $awardData) {
                    $mapped = [
                        'title' => $awardData['title'] ?? '',
                        'awarder' => $awardData['awarder'] ?? '',
                        'summary' => $awardData['summary'] ?? '',
                        'awarded_at' => $awardData['awarded_at'] ?? $awardData['date'] ?? null,
                    ];
                    (new CreateAward($mapped, $user))->handle();
                }
            }

            // Process Certificates
            if (isset($data['certificates'])) {
                foreach ($data['certificates'] as $certData) {
                    $mapped = [
                        'name' => $certData['name'] ?? '',
                        'date' => $certData['date'] ?? null,
                        'url' => $certData['url'] ?? $certData['website'] ?? null,
                    ];
                    (new CreateCertificate($mapped, $user))->handle();
                }
            }

            // Process Publications
            if (isset($data['publications'])) {
                foreach ($data['publications'] as $pubData) {
                    $mapped = [
                        'name' => $pubData['name'] ?? '',
                        'date' => $pubData['date'] ?? $pubData['releaseDate'] ?? null,
                        'issuer' => $pubData['issuer'] ?? $pubData['publisher'] ?? '',
                        'url' => $pubData['url'] ?? $pubData['website'] ?? null,
                    ];
                    (new CreatePublication($mapped, $user))->handle();
                }
            }

            // Process Skills
            if (isset($data['skills'])) {
                foreach ($data['skills'] as $skillData) {
                    $mapped = [
                        'name' => $skillData['name'] ?? '',
                        'level' => $skillData['level'] ?? '',
                        'keywords' => isset($skillData['keywords']) ? (is_array($skillData['keywords']) ? implode(', ', $skillData['keywords']) : $skillData['keywords']) : '',
                    ];
                    (new CreateSkill($mapped, $user))->handle();
                }
            }

            // Process Languages
            if (isset($data['languages'])) {
                foreach ($data['languages'] as $langData) {
                    $mapped = [
                        'language' => $langData['language'] ?? '',
                        'fluency' => $langData['fluency'] ?? '',
                    ];
                    (new CreateLanguage($mapped, $user))->handle();
                }
            }

            // Process Interests
            if (isset($data['interests'])) {
                foreach ($data['interests'] as $interestData) {
                    $mapped = [
                        'name' => $interestData['name'] ?? '',
                        'keywords' => isset($interestData['keywords']) ? (is_array($interestData['keywords']) ? implode(', ', $interestData['keywords']) : $interestData['keywords']) : '',
                    ];
                    (new CreateInterest($mapped, $user))->handle();
                }
            }

            // Process References
            if (isset($data['references'])) {
                foreach ($data['references'] as $refData) {
                    $mapped = [
                        'name' => $refData['name'] ?? '',
                        'keywords' => $refData['reference'] ?? '', // JSON Resume uses 'reference' for the text
                    ];
                    (new CreateReference($mapped, $user))->handle();
                }
            }

            // Process Projects
            if (isset($data['projects'])) {
                foreach ($data['projects'] as $projectData) {
                    $mapped = [
                        'name' => $projectData['name'] ?? '',
                        'start_date' => $projectData['start_date'] ?? $projectData['startDate'] ?? null,
                        'end_date' => $projectData['end_date'] ?? $projectData['endDate'] ?? null,
                        'url' => $projectData['url'] ?? $projectData['website'] ?? null,
                        'description' => $projectData['description'] ?? '',
                    ];
                    $project = (new CreateProject($mapped, $user))->handle();
                    if (isset($projectData['highlights'])) {
                        foreach ($projectData['highlights'] as $highlight) {
                            (new \App\Actions\Resume\Project\CreateHighlight(['highlight' => is_array($highlight) ? ($highlight['highlight'] ?? '') : $highlight], $project))->handle();
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
