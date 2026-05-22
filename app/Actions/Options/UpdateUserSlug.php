<?php

namespace App\Actions\Options;

use App\Models\User;

class UpdateUserSlug
{
    public function __construct(
        private User $user,
        private string $slug
    ) {}

    public function handle(): bool
    {
        return $this->user->update([
            'slug' => $this->slug,
        ]);
    }
}
