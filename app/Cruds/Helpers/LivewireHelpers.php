<?php

namespace App\Cruds\Helpers;

use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class LivewireHelpers
{
    public static function getLivewireInputRecipe(string $name, string $group, bool $isLive = false): InputComponentRecipe
    {
        return new InputComponentRecipe(
            attributeBag: (new DefaultAttributeBag)
                ->setInputAttributes(self::getLivewireAttributes($name, $group, $isLive))
        );
    }

    public static function getLivewireAttributes(string $name, string $group, bool $isLive = false): array
    {
        $live = $isLive ? '.live' : '';

        return [
            "wire:model{$live}" => self::getDotNotationName($group, $name),
        ];
    }

    public static function getDotNotationName(string $group, array|string $name): string
    {
        if (is_array($name)) {
            $name = implode('.', $name);
        }

        return "{$group}.{$name}";
    }
}



