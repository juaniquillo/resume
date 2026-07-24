<?php

namespace App\Actions\Resume\Interest;

use App\Models\Interest;

class UpdateInterest
{
    public function __construct(
        private array $data,
        private Interest $interest
    ) {}

    public function handle(): bool
    {
        return $this->interest->update($this->data);
    }
}
