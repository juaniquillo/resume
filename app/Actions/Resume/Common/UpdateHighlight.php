<?php

namespace App\Actions\Resume\Common;

use App\Cruds\Helpers\FormHelpers;
use Illuminate\Database\Eloquent\Model;

class UpdateHighlight
{
    public function __construct(
        private array $data,
        private Model $highlight
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->highlight->update($data);
    }
}
