<?php

namespace App\Actions\Resume\Export;

use App\Actions\Resume\Export\Builders\AwardsBuilder;
use App\Actions\Resume\Export\Builders\BasicsBuilder;
use App\Actions\Resume\Export\Builders\CertificatesBuilder;
use App\Actions\Resume\Export\Builders\EducationBuilder;
use App\Actions\Resume\Export\Builders\InterestsBuilder;
use App\Actions\Resume\Export\Builders\LanguagesBuilder;
use App\Actions\Resume\Export\Builders\MetaBuilder;
use App\Actions\Resume\Export\Builders\ProjectsBuilder;
use App\Actions\Resume\Export\Builders\PublicationsBuilder;
use App\Actions\Resume\Export\Builders\ReferencesBuilder;
use App\Actions\Resume\Export\Builders\SkillsBuilder;
use App\Actions\Resume\Export\Builders\VolunteerBuilder;
use App\Actions\Resume\Export\Builders\WorkBuilder;
use App\Models\GeneralOption;
use App\Models\User;
use App\Presenters\Resume\ResumeDataLoader;

class BuildResumeArray
{
    public function __construct(
        private User $user,
        private ?GeneralOption $generalOptions = null
    ) {}

    public function handle(): array
    {
        resolve(ResumeDataLoader::class)->clearCache($this->user->id);

        $generalOptions = $this->generalOptions ?? $this->user->generalOptions;

        /** @var GeneralOption|null $generalOptions */
        $data = [
            'meta' => (new MetaBuilder)->handle($generalOptions),
            'basics' => (new BasicsBuilder)->handle($this->user, $generalOptions),
        ];

        if ($work = (new WorkBuilder)->handle($this->user)) {
            $data['work'] = $work;
        }

        if ($volunteer = (new VolunteerBuilder)->handle($this->user)) {
            $data['volunteer'] = $volunteer;
        }

        if ($education = (new EducationBuilder)->handle($this->user)) {
            $data['education'] = $education;
        }

        if ($awards = (new AwardsBuilder)->handle($this->user)) {
            $data['awards'] = $awards;
        }

        if ($certificates = (new CertificatesBuilder)->handle($this->user)) {
            $data['certificates'] = $certificates;
        }

        if ($publications = (new PublicationsBuilder)->handle($this->user)) {
            $data['publications'] = $publications;
        }

        if ($skills = (new SkillsBuilder)->handle($this->user)) {
            $data['skills'] = $skills;
        }

        if ($languages = (new LanguagesBuilder)->handle($this->user)) {
            $data['languages'] = $languages;
        }

        if ($interests = (new InterestsBuilder)->handle($this->user)) {
            $data['interests'] = $interests;
        }

        if ($references = (new ReferencesBuilder)->handle($this->user)) {
            $data['references'] = $references;
        }

        if ($projects = (new ProjectsBuilder)->handle($this->user)) {
            $data['projects'] = $projects;
        }

        return $data;
    }
}
