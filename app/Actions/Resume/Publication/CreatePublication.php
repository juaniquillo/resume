<?php

namespace App\Actions\Resume\Publication;

use App\Models\Publication;
use App\Models\User;

class CreatePublication
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Publication
    {
        /** @var Publication */
        return $this->user->publications()->create($this->data);
    }
}
