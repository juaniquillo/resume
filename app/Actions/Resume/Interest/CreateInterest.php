<?php

namespace App\Actions\Resume\Interest;

use App\Models\Interest;
use App\Models\User;

class CreateInterest
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Interest
    {
        /** @var Interest */
        return $this->user->interests()->create($this->data);
    }
}
