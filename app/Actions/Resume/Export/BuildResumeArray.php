<?php

namespace App\Actions\Resume\Export;

use App\Models\Basic;
use App\Models\Education;
use App\Models\Interest;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Work;
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

        $data = [
            'basics' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
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

        if ($basics) {
            $data['basics'] = array_merge($data['basics'], $basics->toArray());

            if ($basics->image) {
                $data['basics']['image'] = route('image.serve', $basics->uuid);
            }

            if ($basics->location) {
                $data['basics']['location'] = $basics->location->toArray();
            }
            if ($basics->profiles->isNotEmpty()) {
                $data['basics']['profiles'] = $basics->profiles->toArray();
            }
        }

        $data['work'] = $this->user->works()->with('highlights')->get()->map(function (Model $work) {
            /** @var Work $work */
            $workArray = $work->toArray();
            $workArray['highlights'] = $work->highlights->pluck('highlight')->toArray();

            return $workArray;
        })->toArray();

        $data['volunteer'] = $this->user->volunteers()->with('highlights')->get()->map(function (Model $volunteer) {
            /** @var Volunteer $volunteer */
            $volunteerArray = $volunteer->toArray();
            $volunteerArray['highlights'] = $volunteer->highlights->pluck('highlight')->toArray();

            return $volunteerArray;
        })->toArray();

        $data['education'] = $this->user->education()->with('courses')->get()->map(function (Model $edu) {
            /** @var Education $edu */
            $eduArray = $edu->toArray();
            $eduArray['courses'] = $edu->courses->pluck('course')->toArray();

            return $eduArray;
        })->toArray();

        $data['awards'] = $this->user->awards()->get()->toArray();
        $data['certificates'] = $this->user->certificates()->get()->toArray();
        $data['publications'] = $this->user->publications()->get()->toArray();

        $data['skills'] = $this->user->skills()->get()->map(function (Model $skill) {
            /** @var Skill $skill */
            $skillArray = $skill->toArray();
            if (is_string($skillArray['keywords'] ?? null)) {
                $skillArray['keywords'] = array_map('trim', explode(',', $skillArray['keywords']));
            }

            return $skillArray;
        })->toArray();

        $data['languages'] = $this->user->languages()->get()->toArray();

        $data['interests'] = $this->user->interests()->get()->map(function (Model $interest) {
            /** @var Interest $interest */
            $interestArray = $interest->toArray();
            if (is_string($interestArray['keywords'] ?? null)) {
                $interestArray['keywords'] = array_map('trim', explode(',', $interestArray['keywords']));
            }

            return $interestArray;
        })->toArray();

        $data['references'] = $this->user->references()->get()->toArray();

        $data['projects'] = $this->user->projects()->with('highlights')->get()->map(function (Model $project) {
            /** @var Project $project */
            $projectArray = $project->toArray();
            $projectArray['highlights'] = $project->highlights->pluck('highlight')->toArray();

            return $projectArray;
        })->toArray();

        return $data;
    }
}
