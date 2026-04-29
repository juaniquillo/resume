<?php

namespace App\Actions\Resume\Publication;

use App\Models\Publication;

class UpdatePublication
{
    public function __construct(
        private array $data,
        private Publication $publication
    ) {}

    public function handle(): bool
    {
        return $this->publication->update($this->data);
    }
}
