<?php

namespace App\Actions\Resume\Export;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Squema\Awards\AwardsCrud;
use App\Cruds\Squema\Basics\BasicsCrud;
use App\Cruds\Squema\Basics\Inputs\EmailFactory;
use App\Cruds\Squema\Basics\Inputs\NameFactory;
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
use App\Models\Education;
use App\Models\Project;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Work;
use App\Presenters\Resume\ResumeDataLoader;
use Exception;
use Illuminate\Database\Eloquent\Model;

class BuildResumeArray
{
    public function __construct(
        private User $user
    ) {}

    public function handle(): array
    {
        app(ResumeDataLoader::class)->clearCache($this->user->id);

        /** @var Basic|null $basics */
        $basics = $this->user->resumeBasics();

        if (! $basics || (! $basics->{NameFactory::NAME} || ! $basics->{EmailFactory::NAME})) {
            throw new Exception(BasicsCrud::MISSING_BASICS_ERROR);
        }

        $data = [
            'basics' => [],
        ];

        $data['basics'] = BasicsCrud::build()->make()->execute(new ModelToExportAction($basics))->toArray();

        if ($basics->location) {
            $data['basics']['location'] = LocationsCrud::build()->make()->execute(new ModelToExportAction($basics->location))->toArray();
        }

        if ($basics->profiles->isNotEmpty()) {
            $profilesCrud = ProfilesCrud::build()->make();
            $data['basics']['profiles'] = $basics->profiles->map(function (Model $profile) use ($profilesCrud) {
                return $profilesCrud->execute(
                    new ModelToExportAction($profile)
                )->toArray();
            })->toArray();
        }

        $work = $this->user->resumeWorks();
        if ($work->isNotEmpty()) {
            $data['work'] = $work->map(function (Model $work) {
                /** @var Work $work */
                $workArray = WorksCrud::build()->make()->execute(new ModelToExportAction($work))->toArray();
                $workArray['highlights'] = $work->highlights->pluck('highlight')->toArray();

                return $workArray;
            })->toArray();
        }

        $volunteer = $this->user->resumeVolunteers();
        if ($volunteer->isNotEmpty()) {
            $data['volunteer'] = $volunteer->map(function (Model $volunteer) {
                /** @var Volunteer $volunteer */
                $volunteerArray = VolunteersCrud::build()->make()->execute(new ModelToExportAction($volunteer))->toArray();
                $volunteerArray['highlights'] = $volunteer->highlights->pluck('highlight')->toArray();

                return $volunteerArray;
            })->toArray();
        }

        $education = $this->user->resumeEducation();
        if ($education->isNotEmpty()) {
            $data['education'] = $education->map(function (Model $edu) {
                /** @var Education $edu */
                $eduArray = EducationCrud::build()->make()->execute(new ModelToExportAction($edu))->toArray();
                $eduArray['courses'] = $edu->courses->pluck('course')->toArray();

                return $eduArray;
            })->toArray();
        }

        $awards = $this->user->resumeAwards();
        if ($awards->isNotEmpty()) {
            $data['awards'] = $awards->map(function (Model $award) {
                return AwardsCrud::build()->make()->execute(new ModelToExportAction($award))->toArray();
            })->toArray();
        }

        $certificates = $this->user->resumeCertificates();
        if ($certificates->isNotEmpty()) {
            $data['certificates'] = $certificates->map(function (Model $cert) {
                return CertificatesCrud::build()->make()->execute(new ModelToExportAction($cert))->toArray();
            })->toArray();
        }

        $publications = $this->user->resumePublications();
        if ($publications->isNotEmpty()) {
            $data['publications'] = $publications->map(function (Model $pub) {
                return PublicationsCrud::build()->make()->execute(new ModelToExportAction($pub))->toArray();
            })->toArray();
        }

        $skills = $this->user->resumeSkills();
        if ($skills->isNotEmpty()) {
            $data['skills'] = $skills->map(function (Model $skill) {
                return SkillsCrud::build()->make()->execute(new ModelToExportAction($skill))->toArray();
            })->toArray();
        }

        $languages = $this->user->resumeLanguages();
        if ($languages->isNotEmpty()) {
            $data['languages'] = $languages->map(function (Model $lang) {
                return LanguagesCrud::build()->make()->execute(new ModelToExportAction($lang))->toArray();
            })->toArray();
        }

        $interests = $this->user->resumeInterests();
        if ($interests->isNotEmpty()) {
            $data['interests'] = $interests->map(function (Model $interest) {
                return InterestsCrud::build()->make()->execute(new ModelToExportAction($interest))->toArray();
            })->toArray();
        }

        $references = $this->user->resumeReferences();
        if ($references->isNotEmpty()) {
            $data['references'] = $references->map(function (Model $ref) {
                return ReferencesCrud::build()->make()->execute(new ModelToExportAction($ref))->toArray();
            })->toArray();
        }

        $projects = $this->user->resumeProjects();
        if ($projects->isNotEmpty()) {
            $data['projects'] = $projects->map(function (Model $project) {
                /** @var Project $project */
                $projectArray = ProjectsCrud::build()->make()->execute(new ModelToExportAction($project))->toArray();
                $projectArray['highlights'] = $project->highlights->pluck('highlight')->toArray();

                return $projectArray;
            })->toArray();
        }

        return $data;
    }
}
