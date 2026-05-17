<?php

namespace App\Presenters;

use App\Models\User;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\AwardsPresenter;
use App\Presenters\Resume\BasicsPresenter;
use App\Presenters\Resume\CertificatesPresenter;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use App\Presenters\Resume\EducationPresenter;
use App\Presenters\Resume\InterestsPresenter;
use App\Presenters\Resume\LanguagesPresenter;
use App\Presenters\Resume\ProjectsPresenter;
use App\Presenters\Resume\PublicationsPresenter;
use App\Presenters\Resume\ReferencesPresenter;
use App\Presenters\Resume\SkillsPresenter;
use App\Presenters\Resume\SummaryPresenter;
use App\Presenters\Resume\VolunteersPresenter;
use App\Presenters\Resume\WorkPresenter;
use App\Presenters\Themes\DefaultPresenterTheme;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ResumePresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private User $user,
        private ?PresenterTheme $theme = new DefaultPresenterTheme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|Htmlable
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->containerThemes())
            ->setContents(array_filter([
                'basics' => (new BasicsPresenter($this->user, $this->theme))->present(),
                'summary' => (new SummaryPresenter($this->user, $this->theme))->present(),
                'work' => (new WorkPresenter($this->user, $this->theme))->present(),
                'volunteers' => (new VolunteersPresenter($this->user, $this->theme))->present(),
                'education' => (new EducationPresenter($this->user, $this->theme))->present(),
                'awards' => (new AwardsPresenter($this->user, $this->theme))->present(),
                'certificates' => (new CertificatesPresenter($this->user, $this->theme))->present(),
                'publications' => (new PublicationsPresenter($this->user, $this->theme))->present(),
                'skills' => (new SkillsPresenter($this->user, $this->theme))->present(),
                'languages' => (new LanguagesPresenter($this->user, $this->theme))->present(),
                'interests' => (new InterestsPresenter($this->user, $this->theme))->present(),
                'references' => (new ReferencesPresenter($this->user, $this->theme))->present(),
                'projects' => (new ProjectsPresenter($this->user, $this->theme))->present(),
            ]));
    }

    public function presentCached(): string
    {
        $key = $this->getCacheKey();

        return Cache::rememberForever($key, fn () => (string) $this->present()->toHtml());
    }

    private function getCacheKey(): string
    {
        $version = Cache::get("resume:{$this->user->id}:v", 1);
        $themeHash = md5(get_class($this->theme));

        return "resume:{$this->user->id}:v{$version}:{$themeHash}";
    }

    public function clearCache(): void
    {
        $key = $this->getCacheKey();
        Cache::forget($key);
    }
}
