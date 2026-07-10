<?php

namespace App\Livewire\Concerns;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

trait IsLivewireModal
{
    public const EDIT_ICON = 'pencil-square';

    public const CREATE_ICON = 'plus-circle';

    public function modalButton(string $label, int|string $id, ?string $variant = null, ?string $icon = null, ?string $size = null, ?array $themes = null): CompoundComponent|BackendComponent
    {
        $button = FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setContent($label)
            ->setTheme('cursor', 'pointer');

        if ($variant) {
            $button->setAttribute('variant', $variant);
        }

        if ($icon) {
            $button->setAttribute('icon', $icon);
        }

        if ($size) {
            $button->setAttribute('size', $size);
        }

        if ($themes) {
            $button->setThemes($themes);
        }

        $trigger = FluxComponentBuilder::make(FluxComponentEnum::MODAL_TRIGGER)
            ->setAttribute('name', $id)
            ->setContent($button);

        return $trigger;
    }

    public function modalComponent(int|string $id, BackendComponent|CompoundComponent $content, string|BackendComponent|CompoundComponent|null $heading = null, ?array $themes = null): CompoundComponent|BackendComponent
    {
        $contents = [];

        if ($heading) {
            $contents['heading'] = FluxComponentBuilder::make(FluxComponentEnum::HEADING)
                ->setContent($heading);
        }

        $contents['content'] = $content;

        $modal = FluxComponentBuilder::make(FluxComponentEnum::MODAL)
            ->setAttribute('name', $id)
            ->setContents($contents);

        if ($themes) {
            $modal->setThemes($themes);
        }

        return $modal;
    }
}
