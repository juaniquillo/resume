<?php

namespace App\Actions\Resume\Work;

use App\Models\Work;

class UpdateWork
{
    public function __construct(
        private array $data,
        private Work $work
    ) {}

    public function handle(): bool
    {
        return $this->work->update($this->data);
    }
}
