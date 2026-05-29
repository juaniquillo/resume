<?php

namespace App\Presenters\Resume;

use App\Models\ResumeExport;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class DownloadsPresenter
{
    use CanComposeResumeComponents;

    /**
     * @param  Collection<int, ResumeExport>  $downloads
     */
    public function __construct(
        private Collection $downloads,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->downloads->isEmpty()) {
            return null;
        }

        $items = $this->downloads->map(function (ResumeExport $export) {
            return $this->compose(ComponentEnum::LINK)
                ->setAttribute('href', route('resume.download', $export->uuid))
                ->setThemes($this->theme->socialBadgeThemes())
                ->setContents([
                    'icon' => $this->compose(ComponentEnum::SPAN)
                        ->setThemes($this->theme->iconThemes())
                        ->setAttributes([
                            'style' => sprintf(
                                'background-color: currentColor; mask-image: url(%s); -webkit-mask-image: url(%s); mask-size: contain; mask-repeat: no-repeat; mask-position: center;',
                                asset('images/download.svg'),
                                asset('images/download.svg')
                            ),
                        ]),
                    'label' => $this->compose(ComponentEnum::SPAN)
                        ->setContent($export->type->label()),
                ]);
        })->toArray();

        $content = $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->downloadsContainerThemes())
            ->setContents($items);

        return $this->section(__('Downloads'), $content);
    }
}
