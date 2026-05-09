<?php

namespace App\Actions\Resume\Education;

use App\Models\Education;
use Illuminate\Database\Eloquent\Model;

class CreateCourse
{
    public function __construct(
        private array $data,
        private Education $education
    ) {}

    public function handle(): Model
    {
        return $this->education->courses()->create($this->data);
    }
}
