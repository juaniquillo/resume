<?php

namespace App\Cruds\Squema\Profiles;

use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Profiles\Inputs\BasicsFactory;
use App\Cruds\Squema\Profiles\Inputs\NetworkFactory;
use App\Cruds\Squema\Profiles\Inputs\UrlFactory;
use App\Cruds\Squema\Profiles\Inputs\UsernameFactory;

class ProfilesCrud implements CrudInterface
{
    use IsCrud;


    public static function inputsArray(): array
    {
        return [
            BasicsFactory::make(),
            NetworkFactory::make(),
            UsernameFactory::make(),
            UrlFactory::make(),
        ];
    }

    public static function formAction(): string
    {
        return '';
    }

}
