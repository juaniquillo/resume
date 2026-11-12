<?php

namespace App\Livewire\Concerns;

use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ContentComponent;
use Juaniquillo\FluxBackendComponents\Factories\FluxComponentFactory;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

trait IsLivewireModal
{
    public const EDIT_ICON = 'pencil-square';

    public const CREATE_ICON = 'plus-circle';

    public function modalButton(string $label, int|string $id, ?string $variant = null, ?string $icon = null, ?string $size = null, ?array $themes = null): CompoundComponent|BackendComponent|ContentComponent
    {
        $button = FluxComponentFactory::make(FluxComponentEnum::BUTTON)
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

        $trigger = $this->modalTrigger($id)
            ->setContent($button);

        return $trigger;
    }

    public function modalComponent(int|string $id, BackendComponent|CompoundComponent|string $content, string|BackendComponent|CompoundComponent|null $heading = null, ?array $themes = null, bool $flyout = false): CompoundComponent|BackendComponent|ContentComponent
    {
        $contents = [];

        if ($heading) {
            $contents['heading'] = FluxComponentFactory::make(FluxComponentEnum::HEADING)
                ->setContent($heading);
        }

        $contents['content'] = $content;

        $modal = FluxComponentFactory::make(FluxComponentEnum::MODAL)
            ->setAttribute('name', $id)
            ->setContents($contents);

        if ($flyout) {
            $modal->setAttribute('flyout', 'true')
                ->setAttribute('position', 'right');
        }

        if ($themes) {
            $modal->setThemes($themes);
        }

        return $modal;
    }

    public function modalTrigger(int|string $id): CompoundComponent|BackendComponent|ContentComponent
    {
        return FluxComponentFactory::make(FluxComponentEnum::MODAL_TRIGGER)
            ->setAttribute('name', $id);
    }
}
