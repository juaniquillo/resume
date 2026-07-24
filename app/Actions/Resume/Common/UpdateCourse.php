<?php

namespace App\Actions\Resume\Common;

use Illuminate\Database\Eloquent\Model;

class UpdateCourse
{
    public function __construct(
        private array $data,
        private Model $course
    ) {}

    public function handle(): bool
    {
        return $this->course->update($this->data);
    }
}
