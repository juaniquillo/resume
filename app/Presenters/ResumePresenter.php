<?php

namespace App\Presenters;

use App\Models\GeneralOption;
use App\Models\User;
use App\Presenters\Cache\ResumePresenterCacheManager;
use App\Presenters\Contracts\CacheManager;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\AwardsPresenter;
use App\Presenters\Resume\BasicsPresenter;
use App\Presenters\Resume\CertificatesPresenter;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use App\Presenters\Resume\DownloadsPresenter;
use App\Presenters\Resume\EducationPresenter;
use App\Presenters\Resume\InterestsPresenter;
use App\Presenters\Resume\LanguagesPresenter;
use App\Presenters\Resume\ProjectsPresenter;
use App\Presenters\Resume\PublicationsPresenter;
use App\Presenters\Resume\ReferencesPresenter;
use App\Presenters\Resume\ResumeDataLoader;
use App\Presenters\Resume\SkillsPresenter;
use App\Presenters\Resume\SummaryPresenter;
use App\Presenters\Resume\VolunteersPresenter;
use App\Presenters\Resume\WorkPresenter;
use App\Presenters\Themes\DefaultPresenterTheme;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ResumePresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private User $user,
        private ?PresenterTheme $theme = new DefaultPresenterTheme,
        private bool $isPdf = false,
        private ?CacheManager $cacheManager = null,
    ) {}

    public function present(): BackendComponent|CompoundComponent|Htmlable
    {
        /** @var array<string, bool> $settings */
        $settings = (array) ($this->user->sectionVisibility->settings ?? []);
        $data = (new ResumeDataLoader)->load($this->user);
        /** @var ?GeneralOption $generalOptions */
        $generalOptions = $this->user->generalOptions;

        $sections = [
            'basics' => (new BasicsPresenter(
                $data->basics,
                $this->theme,
                $generalOptions
            ))->present(),
            'summary' => (! ($settings['summary'] ?? false)) ? (new SummaryPresenter($data->basics, $this->theme))->present() : null,
            'work' => (! ($settings['work'] ?? false)) ? (new WorkPresenter($data->works, $this->theme))->present() : null,
            'volunteers' => (! ($settings['volunteers'] ?? false)) ? (new VolunteersPresenter($data->volunteers, $this->theme))->present() : null,
            'education' => (! ($settings['education'] ?? false)) ? (new EducationPresenter($data->education, $this->theme))->present() : null,
            'awards' => (! ($settings['awards'] ?? false)) ? (new AwardsPresenter($data->awards, $this->theme))->present() : null,
            'certificates' => (! ($settings['certificates'] ?? false)) ? (new CertificatesPresenter($data->certificates, $this->theme))->present() : null,
            'publications' => (! ($settings['publications'] ?? false)) ? (new PublicationsPresenter($data->publications, $this->theme))->present() : null,
            'skills' => (! ($settings['skills'] ?? false)) ? (new SkillsPresenter($data->skills, $this->theme))->present() : null,
            'languages' => (! ($settings['languages'] ?? false)) ? (new LanguagesPresenter($data->languages, $this->theme))->present() : null,
            'interests' => (! ($settings['interests'] ?? false)) ? (new InterestsPresenter($data->interests, $this->theme))->present() : null,
            'references' => (! ($settings['references'] ?? false)) ? (new ReferencesPresenter($data->references, $this->theme))->present() : null,
            'projects' => (! ($settings['projects'] ?? false)) ? (new ProjectsPresenter($data->projects, $this->theme))->present() : null,
            'downloads' => (! $this->isPdf) ? (new DownloadsPresenter($data->downloads, $this->theme))->present() : null,
        ];

        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->containerThemes())
            ->setContents(array_filter($sections));
    }

    public function getTheme(): PresenterTheme
    {
        return $this->theme;
    }

    public function presentCached(): string
    {
        return $this->getCacheManager()
            ->present($this);
    }

    public function clearCache(): void
    {
        $this->getCacheManager()->clearCache();
    }

    public function getCacheKey(): string
    {
        return $this->getCacheManager()->getCacheKey();
    }

    private function getCacheManager(): CacheManager
    {
        return $this->cacheManager ?? (new ResumePresenterCacheManager($this->user, $this->theme));
    }
}
