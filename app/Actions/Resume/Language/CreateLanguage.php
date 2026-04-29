<?php

namespace App\Actions\Resume\Language;

use App\Models\Language;
use App\Models\User;

class CreateLanguage
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Language
    {
        /** @var Language */
        return $this->user->languages()->create($this->data);
    }
}
