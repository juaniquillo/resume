<?php

namespace App\Actions\Options;

use App\Models\User;

class UpdateGeneralOptions
{
    public function __construct(
        private User $user,
        private array $data
    ) {}

    public function handle(): void
    {
        $this->user->generalOptions()->updateOrCreate(
            ['user_id' => $this->user->id],
            [
                'slug' => $this->data['slug'],
                'theme' => $this->data['theme'],
            ]
        );
    }
}
