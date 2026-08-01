<?php

namespace App\Actions\Resume\Reference;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Reference;
use App\Models\User;

class CreateReference
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Reference
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Reference */
        return $this->user->references()->create($data);
    }
}
