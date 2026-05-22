<?php

namespace App\Presenters\Resume;

use App\Models\Publication;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class PublicationsPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $publications,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->publications->isEmpty()) {
            return null;
        }

        $items = $this->publications->map(function (Model $model) {
            /** @var Publication $pub */
            $pub = $model;

            return $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->itemContainerThemes())
                ->setContents([
                    'name' => $pub->url
                        ? $this->compose(ComponentEnum::LINK)
                            ->setAttribute('href', $pub->url)
                            ->setAttribute('target', '_blank')
                            ->setContent(
                                $this->compose(ComponentEnum::H3)
                                    ->setThemes($this->theme->itemTitleThemes())
                                    ->setContent($pub->name)
                            )
                        : $this->compose(ComponentEnum::H3)
                            ->setThemes($this->theme->itemTitleThemes())
                            ->setContent($pub->name),
                    'details' => $this->compose(ComponentEnum::DIV)
                        ->setThemes($this->theme->itemDetailsThemes())
                        ->setContents([
                            'issuer' => $this->compose(ComponentEnum::SPAN)
                                ->setThemes($this->theme->subTitleThemes())
                                ->setContent($pub->issuer),
                            'date' => $this->compose(ComponentEnum::SPAN)
                                ->setThemes($this->theme->dateThemes())
                                ->setContent($pub->date->format('M Y')),
                        ]),
                ]);
        })->toArray();

        return $this->section('Publications',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->publicationsContainerThemes())
                ->setContents($items)
        );
    }
}
