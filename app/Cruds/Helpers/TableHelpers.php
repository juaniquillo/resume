<?php

namespace App\Cruds\Helpers;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Enums\ProcessStatus;
use BackedEnum;
use Carbon\CarbonImmutable;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Livewire\Component;
use Stringable;

final class TableHelpers
{
    public static function make(): static
    {
        return new self;
    }

    public static function tableModal(int|string $id, string|BackendComponent|CompoundComponent $content, string $heading = '', string $triggerType = 'primary', string $buttonLabel = 'View'): BackendComponent|CompoundComponent
    {
        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => FluxComponentBuilder::make(FluxComponentEnum::MODAL_TRIGGER)
                    ->setAttribute('name', "flux-modal-confirm-{$id}")
                    ->setContent(
                        FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                            ->setAttribute('variant', $triggerType)
                            ->setAttribute('size', 'xs')
                            ->setTheme('cursor', 'pointer')
                            ->setContent($buttonLabel)
                    ),
                'modal' => FluxComponentBuilder::make(FluxComponentEnum::MODAL)
                    ->setAttribute('name', "flux-modal-confirm-{$id}")
                    // ->setAttribute(':dismissible', 'false')
                    ->setContents([
                        FluxComponentBuilder::make(FluxComponentEnum::HEADING)
                            ->setContent($heading),
                        $content,
                    ]),
            ]);
    }

    public static function summaryModal(Stringable|BackedEnum|string|array|null $value, int $id, string $label): BackendComponent|CompoundComponent
    {
        if ($value === null) {
            return TableHelpers::emptyValue();
        }

        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        return TableHelpers::tableModal(
            id: "summary-{$id}",
            triggerType: 'ghost',
            heading: $label,
            content: ComponentBuilder::make(ComponentEnum::DIV)
                ->setContent($value)
                ->setTheme('margin', 'top-sm')
        );
    }

    public static function editButton(string $route): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setAttribute('href', $route)
            ->setContent('Edit')
            ->setAttribute('size', 'xs')
            ->setAttribute('icon', 'pencil-square')
            ->setTheme('cursor', 'pointer');
    }

    public static function deleteButton(string $route): BackendComponent|CompoundComponent
    {
        return ComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', $route)
            ->setAttribute('method', 'delete')
            ->setContent(
                self::baseDeleteButton()
                    ->setAttribute('type', 'submit')
                    ->setAttribute('onclick', "return confirm('Are you sure you want to delete this record?')")
            );
    }

    public static function livewireDeleteButton(string $action, string $confirmMessage = 'Are you sure you want to delete this record?'): BackendComponent|CompoundComponent
    {
        $button = self::baseDeleteButton()
            ->setAttribute('type', 'button')
            ->setAttribute('wire:confirm', $confirmMessage)
            ->setAttribute('wire:click.prevent', $action);

        return $button;
    }

    /** @param class-string<Component>|BackedEnum $component */
    public function liveWireComponent(string|BackedEnum $component, int|string $id, array $params): BackendComponent|CompoundComponent
    {
        $component = ComponentBuilder::make($component)
            ->setLivewire()
            ->setLivewireKey($id)
            ->setLivewireParams($params);

        return $component;
    }

    public static function baseDeleteButton(): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setContent('Delete')
            ->setAttribute('size', 'xs')
            ->setAttribute('variant', 'danger')
            ->setAttribute('icon', 'trash')
            ->setTheme('cursor', 'pointer');
    }

    public static function badge(string $content, string $color, array $extraAttributes = []): BackendComponent|CompoundComponent
    {
        $attributes = [
            'color' => $color,
        ];

        $attributes = array_merge($attributes, $extraAttributes);

        return FluxComponentBuilder::make(FluxComponentEnum::BADGE)
            ->setAttributes($attributes)
            ->setContent($content);
    }

    public static function statusBadge(ProcessStatus|string $status): BackendComponent|CompoundComponent
    {
        if (is_string($status)) {
            $status = ProcessStatus::from($status);
        }

        return self::badge(
            content: $status->label(),
            color: $status->color(),
            extraAttributes: [
                'inset' => 'top bottom',
                'icon' => $status->icon(),
            ]
        );
    }

    public static function booleanBadge(mixed $value): BackendComponent|CompoundComponent
    {
        $value = (bool) $value;

        return self::badge(
            $value ? 'Yes' : 'No',
            $value ? 'green' : 'zinc'
        );
    }

    public static function highlightsButton(string $route): BackendComponent|CompoundComponent
    {
        return self::baseHighlightsButton()
            ->setAttribute('wire:navigate', '')
            ->setAttribute('href', $route);

    }

    public static function baseHighlightsButton(): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setContent('Highlights')
            ->setAttribute('variant', 'primary')
            ->setAttribute('icon', 'sun')
            ->setAttribute('color', 'amber')
            ->setAttribute('size', 'xs')
            ->setTheme('cursor', 'pointer');

    }

    public static function errorTooltip(string $error, string|BackendComponent|CompoundComponent $trigger, string $position = 'top'): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::TOOLTIP)
            ->setAttribute('content', $error)
            ->setAttribute('position', $position)
            ->setContent($trigger);
    }

    public static function tableLink(?string $link, ?string $label = null, string $target = '_blank'): BackendComponent|CompoundComponent
    {
        if (! $link) {
            return TableHelpers::emptyValue();
        }

        return FluxComponentBuilder::make(FluxComponentEnum::LINK)
            ->setAttributes([
                'href' => $link,
                'target' => $target,
            ])
            ->setContent($label ?? $link);
    }

    public static function nl2br(string $value)
    {
        return LocalThemeComponentBuilder::make(ComponentEnum::DIV)
            ->setContent($value)
            ->setTheme('text', 'nl2br');
    }

    public static function emptyValue(string $label = 'N/A'): BackendComponent|CompoundComponent
    {
        return FluxComponentBuilder::make(FluxComponentEnum::BADGE)
            ->setAttribute('variant', 'light')
            ->setContent($label);

    }

    public static function formatDateOutput(?InputInterface $input): void
    {
        $recipe = new TableRowsRecipe(
            value: fn (?CarbonImmutable $value) => DateHelpers::formatDateOutput($value)
        );

        $input->setRecipe($recipe);

    }
}



