<?php

namespace App\Actions\Options;

use App\Models\User;

class UpdateUserTheme
{
    public function __construct(
        private User $user,
        private string $theme
    ) {}

    public function handle(): void
    {
        $this->user->theme()->updateOrCreate(
            ['user_id' => $this->user->id],
            ['theme' => $this->theme]
        );
    }
}
