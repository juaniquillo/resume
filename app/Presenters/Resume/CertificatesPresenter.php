<?php

namespace App\Presenters\Resume;

use App\Models\Certificate;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class CertificatesPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $certificates,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->certificates->isEmpty()) {
            return null;
        }

        $items = $this->certificates->map(function (Certificate $cert) {
            return $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->itemContainerThemes())
                ->setContents([
                    'name' => $cert->url
                        ? $this->compose(ComponentEnum::LINK)
                            ->setAttribute('href', $cert->url)
                            ->setAttribute('target', '_blank')
                            ->setContent(
                                $this->compose(ComponentEnum::H3)
                                    ->setThemes($this->theme->itemTitleThemes())
                                    ->setContent($cert->name)
                            )
                        : $this->compose(ComponentEnum::H3)
                            ->setThemes($this->theme->itemTitleThemes())
                            ->setContent($cert->name),
                    'details' => $this->compose(ComponentEnum::DIV)
                        ->setThemes($this->theme->itemDetailsThemes())
                        ->setContents([
                            'date' => $this->compose(ComponentEnum::SPAN)
                                ->setThemes($this->theme->dateThemes())
                                ->setContent($cert->date->format('M Y')),
                        ]),
                ]);
        })->toArray();

        return $this->section('Certificates',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->certificatesContainerThemes())
                ->setContents($items)
        );
    }
}
