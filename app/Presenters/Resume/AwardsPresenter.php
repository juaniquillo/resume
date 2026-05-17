<?php

namespace App\Presenters\Resume;

use App\Models\Award;
use App\Models\User;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class AwardsPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private User $user,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        /** @var Collection<int, Award> $awards */
        $awards = $this->user->awards()->orderByDesc('awarded_at')->get();

        if ($awards->isEmpty()) {
            return null;
        }

        $items = $awards->map(function (Award $award) {
            return $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->itemContainerThemes())
                ->setContents(array_filter([
                    'title' => $this->compose(ComponentEnum::H3)
                        ->setThemes($this->theme->itemTitleThemes())
                        ->setContent($award->title),
                    'details' => $this->compose(ComponentEnum::DIV)
                        ->setThemes($this->theme->itemDetailsThemes())
                        ->setContents([
                            'awarder' => $this->compose(ComponentEnum::SPAN)
                                ->setThemes($this->theme->subTitleThemes())
                                ->setContent($award->awarder),
                            'date' => $this->compose(ComponentEnum::SPAN)
                                ->setThemes($this->theme->dateThemes())
                                ->setContent($award->awarded_at->format('M Y')),
                        ]),
                    'summary' => $award->summary
                        ? $this->compose(ComponentEnum::PARAGRAPH)
                            ->setThemes($this->theme->summaryThemes())
                            ->setContent($award->summary)
                        : null,
                ]));
        })->toArray();

        return $this->section('Awards',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->awardsContainerThemes())
                ->setContents($items)
        );
    }
}
