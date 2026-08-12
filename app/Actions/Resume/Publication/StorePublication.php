<?php

namespace App\Actions\Resume\Publication;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Publication;
use App\Models\User;

class StorePublication
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Publication
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Publication */
        return $this->user->publications()->create($data);
    }
}
