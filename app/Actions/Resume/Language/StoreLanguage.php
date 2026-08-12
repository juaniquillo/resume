<?php

namespace App\Actions\Resume\Language;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Language;
use App\Models\User;

class StoreLanguage
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Language
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Language */
        return $this->user->languages()->create($data);
    }
}
