<?php

namespace App\Actions\Resume\Education;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Education;
use Illuminate\Database\Eloquent\Model;

class StoreCourse
{
    public function __construct(
        private array $data,
        private Education $education
    ) {}

    public function handle(): Model
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->education->courses()->create($data);
    }
}
