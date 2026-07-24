<?php

namespace App\Actions\Resume\Award;

use App\Models\Award;
use App\Models\User;

class CreateAward
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Award
    {
        /** @var Award */
        return $this->user->awards()->create($this->data);
    }
}
