<?php

namespace App\Actions\Resume\Common;

use Illuminate\Database\Eloquent\Model;

class UpdateHighlight
{
    public function __construct(
        private array $data,
        private Model $highlight
    ) {}

    public function handle(): bool
    {
        return $this->highlight->update($this->data);
    }
}
