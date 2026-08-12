<?php

namespace App\Actions\Resume\Interest;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Interest;
use App\Models\User;

class StoreInterest
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Interest
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Interest */
        return $this->user->interests()->create($data);
    }
}
