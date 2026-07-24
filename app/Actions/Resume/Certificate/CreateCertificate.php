<?php

namespace App\Actions\Resume\Certificate;

use App\Models\Certificate;
use App\Models\User;

class CreateCertificate
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Certificate
    {
        /** @var Certificate */
        return $this->user->certificates()->create($this->data);
    }
}
