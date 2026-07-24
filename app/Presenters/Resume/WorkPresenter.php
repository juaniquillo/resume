<?php

namespace App\Presenters\Resume;

use App\Models\Highlight;
use App\Models\Work;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class WorkPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $works,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->works->isEmpty()) {
            return null;
        }

        $items = $this->works->map(function (Model $model) {
            /** @var Work $work */
            $work = $model;

            return $this->presentWorkEntry($work);
        })->toArray();

        return $this->section('Experience',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->workContainerThemes())
                ->setContents($items)
        );
    }

    private function presentWorkEntry(Work $work): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->itemContainerThemes())
            ->setContents(array_filter([
                'position' => $this->compose(ComponentEnum::H3)
                    ->setThemes($this->theme->itemTitleThemes())
                    ->setContent($work->position),
                'details' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->itemDetailsThemes())
                    ->setContents([
                        'name' => $work->url
                            ? $this->compose(ComponentEnum::LINK)
                                ->setAttribute('href', $work->url)
                                ->setAttribute('target', '_blank')
                                ->setThemes($this->theme->linkThemes())
                                ->setContent(
                                    $this->compose(ComponentEnum::SPAN)
                                        ->setThemes($this->theme->subTitleThemes())
                                        ->setContent($work->name)
                                )
                            : $this->compose(ComponentEnum::SPAN)
                                ->setThemes($this->theme->subTitleThemes())
                                ->setContent($work->name),
                        'dates' => $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->dateThemes())
                            ->setContent(sprintf('%s - %s',
                                $work->starts_at->format('M Y'),
                                $work->ends_at
                                    ? $work->ends_at->format('M Y')
                                    : __('Present')
                            )),
                    ]),
                'summary' => $work->summary
                    ? $this->compose(ComponentEnum::PARAGRAPH)
                        ->setThemes($this->theme->summaryThemes())
                        ->setContent($work->summary)
                    : null,
                'highlights' => $work->highlights->isNotEmpty()
                    ? $this->compose(ComponentEnum::UL)
                        ->setThemes($this->theme->listThemes())
                        ->setContents(
                            $work->highlights->map(function (Model $h) {
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



