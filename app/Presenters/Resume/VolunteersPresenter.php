<?php

namespace App\Presenters\Resume;

use App\Models\Highlight;
use App\Models\Volunteer;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class VolunteersPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $volunteers,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->volunteers->isEmpty()) {
            return null;
        }

        $items = $this->volunteers->map(function (Model $model) {
            /** @var Volunteer $volunteer */
            $volunteer = $model;

            return $this->presentVolunteerEntry($volunteer);
        })->toArray();

        return $this->section('Volunteering',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->volunteersContainerThemes())
                ->setContents($items)
        );
    }

    private function presentVolunteerEntry(Volunteer $volunteer): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->itemContainerThemes())
            ->setContents(array_filter([
                'position' => $this->compose(ComponentEnum::H3)
                    ->setThemes($this->theme->itemTitleThemes())
                    ->setContent($volunteer->position),
                'details' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->itemDetailsThemes())
                    ->setContents([
                        'organization' => $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->subTitleThemes())
                            ->setContent($volunteer->organization),
                        'dates' => $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->dateThemes())
                            ->setContent(sprintf('%s - %s',
                                $volunteer->starts_at->format('M Y'),
                                $volunteer->ends_at
                                    ? $volunteer->ends_at->format('M Y')
                                    : 'Present'
                            )),
                    ]),
                'summary' => $volunteer->summary
                    ? $this->compose(ComponentEnum::PARAGRAPH)
                        ->setThemes($this->theme->summaryThemes())
                        ->setContent($volunteer->summary)
                    : null,
                'highlights' => $volunteer->highlights->isNotEmpty()
                    ? $this->compose(ComponentEnum::UL)
                        ->setThemes($this->theme->listThemes())
                        ->setContents(
                            $volunteer->highlights->map(function (Model $h) {
                                /** @var Highlight $h */
                                return $this->compose(ComponentEnum::LI)
                                    ->setThemes($this->theme->listItemThemes())
                                    ->setContent($h->highlight);
                            })->toArray()
                        )
                    : null,
            ]));
    }
}
