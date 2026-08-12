<?php

namespace App\Actions\Resume\Basics;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Basic;
use App\Models\Profile;

class StoreProfile
{
    public function __construct(
        private array $data,
        private Basic $basics
    ) {}

    public function handle(): Profile
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Profile */
        return $this->basics->profiles()->create($data);
    }
}
