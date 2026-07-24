<?php

namespace App\Actions\Resume\Award;

use App\Models\Award;

class UpdateAward
{
    public function __construct(
        private array $data,
        private Award $award
    ) {}

    public function handle(): bool
    {
        return $this->award->update($this->data);
    }
}
