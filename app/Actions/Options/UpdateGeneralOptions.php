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
                'is_draft' => $this->data['is_draft'] ?? false,
                /**
                 * Security options
                 */
                'hide_phone' => $this->data['hide_phone'] ?? false,
                'hide_email' => $this->data['hide_email'] ?? false,
                'hide_image' => $this->data['hide_image'] ?? false,
                'hide_address' => $this->data['hide_address'] ?? false,
            ]
        );
    }
}
