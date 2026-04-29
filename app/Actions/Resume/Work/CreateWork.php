<?php

namespace App\Actions\Resume\Work;

use App\Models\User;
use App\Models\Work;

class CreateWork
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Work
    {
        /** @var Work */
        return $this->user->works()->create($this->data);
    }
}
