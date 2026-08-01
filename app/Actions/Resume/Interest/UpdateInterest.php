<?php

namespace App\Actions\Resume\Interest;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Interest;

class UpdateInterest
{
    public function __construct(
        private array $data,
        private Interest $interest
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->interest->update($data);
    }
}
