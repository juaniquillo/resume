<?php

namespace App\Actions\Options;

use App\Models\User;

class UpdateSectionVisibility
{
    public function __construct(
        private User $user,
        private array $settings
    ) {}

    public function handle(): void
    {
        $this->user->sectionVisibility()->updateOrCreate(
            ['user_id' => $this->user->id],
            ['settings' => $this->settings]
        );
    }
}



