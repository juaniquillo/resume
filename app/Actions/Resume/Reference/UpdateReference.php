<?php

namespace App\Actions\Resume\Reference;

use App\Models\Reference;

class UpdateReference
{
    public function __construct(
        private array $data,
        private Reference $reference
    ) {}

    public function handle(): bool
    {
        return $this->reference->update($this->data);
    }
}
