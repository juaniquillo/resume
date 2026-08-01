<?php

namespace App\Actions\Resume\Common;

use App\Cruds\Helpers\FormHelpers;
use Illuminate\Database\Eloquent\Model;

class UpdateCourse
{
    public function __construct(
        private array $data,
        private Model $course
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->course->update($data);
    }
}
