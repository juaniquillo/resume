<?php

namespace App\Presenters\Resume;

use App\Models\Publication;
use App\Models\User;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class PublicationsPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private User $user,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        /** @var Collection<int, Publication> $publications */
        $publications = $this->user->publications()->orderByDesc('date')->get();

        if ($publications->isEmpty()) {
            return null;
        }

        $items = $publications->map(function (Publication $pub) {
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
