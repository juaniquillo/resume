<?php

namespace App\Actions\Resume\Certificate;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Certificate;
use App\Models\User;

class StoreCertificate
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Certificate
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Certificate */
        return $this->user->certificates()->create($data);
    }
}
