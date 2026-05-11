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
use Exception;
use Illuminate\Database\Eloquent\Model;

class BuildResumeArray
{
    public function __construct(
        private User $user
    ) {}

    public function handle(): array
    {
        /** @var Basic|null $basics */
        $basics = $this->user->basics()->with(['location', 'profiles'])->first();

        if (! $basics || (! $basics->{NameFactory::NAME} || ! $basics->{EmailFactory::NAME})) {
            throw new Exception(BasicsCrud::MISSING_BASICS_ERROR);
        }

        $data = [
            'basics' => [],
            'work' => [],
            'volunteer' => [],
            'education' => [],
            'awards' => [],
            'certificates' => [],
            'publications' => [],
            'skills' => [],
            'languages' => [],
            'interests' => [],
            'references' => [],
            'projects' => [],
        ];

        $data['basics'] = BasicsCrud::build()->make()->execute(new ModelToExportAction($basics))->toArray();

        if ($basics->location) {
            $data['basics']['location'] = LocationsCrud::build()->make()->execute(new ModelToExportAction($basics->location))->toArray();
        }

        if ($basics->profiles->isNotEmpty()) {
            $data['basics']['profiles'] = $basics->profiles->map(function (Model $profile) {
                return ProfilesCrud::build()->make()->execute(new ModelToExportAction($profile))->toArray();
            })->toArray();
        }

        $data['work'] = $this->user->works()->with('highlights')->get()->map(function (Model $work) {
            /** @var Work $work */
            $workArray = WorksCrud::build()->make()->execute(new ModelToExportAction($work))->toArray();
            $workArray['highlights'] = $work->highlights->pluck('highlight')->toArray();

            return $workArray;
        })->toArray();

        $data['volunteer'] = $this->user->volunteers()->with('highlights')->get()->map(function (Model $volunteer) {
            /** @var Volunteer $volunteer */
            $volunteerArray = VolunteersCrud::build()->make()->execute(new ModelToExportAction($volunteer))->toArray();
            $volunteerArray['highlights'] = $volunteer->highlights->pluck('highlight')->toArray();

            return $volunteerArray;
        })->toArray();

        $data['education'] = $this->user->education()->with('courses')->get()->map(function (Model $edu) {
            /** @var Education $edu */
            $eduArray = EducationCrud::build()->make()->execute(new ModelToExportAction($edu))->toArray();
            $eduArray['courses'] = $edu->courses->pluck('course')->toArray();

            return $eduArray;
        })->toArray();

        $data['awards'] = $this->user->awards()->get()->map(function (Model $award) {
            return AwardsCrud::build()->make()->execute(new ModelToExportAction($award))->toArray();
        })->toArray();

        $data['certificates'] = $this->user->certificates()->get()->map(function (Model $cert) {
            return CertificatesCrud::build()->make()->execute(new ModelToExportAction($cert))->toArray();
        })->toArray();

        $data['publications'] = $this->user->publications()->get()->map(function (Model $pub) {
            return PublicationsCrud::build()->make()->execute(new ModelToExportAction($pub))->toArray();
        })->toArray();

        $data['skills'] = $this->user->skills()->get()->map(function (Model $skill) {
            return SkillsCrud::build()->make()->execute(new ModelToExportAction($skill))->toArray();
        })->toArray();

        $data['languages'] = $this->user->languages()->get()->map(function (Model $lang) {
            return LanguagesCrud::build()->make()->execute(new ModelToExportAction($lang))->toArray();
        })->toArray();

        $data['interests'] = $this->user->interests()->get()->map(function (Model $interest) {
            return InterestsCrud::build()->make()->execute(new ModelToExportAction($interest))->toArray();
        })->toArray();

        $data['references'] = $this->user->references()->get()->map(function (Model $ref) {
            return ReferencesCrud::build()->make()->execute(new ModelToExportAction($ref))->toArray();
        })->toArray();

        $data['projects'] = $this->user->projects()->with('highlights')->get()->map(function (Model $project) {
            /** @var Project $project */
            $projectArray = ProjectsCrud::build()->make()->execute(new ModelToExportAction($project))->toArray();
            $projectArray['highlights'] = $project->highlights->pluck('highlight')->toArray();

            return $projectArray;
        })->toArray();

        return $data;
    }
}
