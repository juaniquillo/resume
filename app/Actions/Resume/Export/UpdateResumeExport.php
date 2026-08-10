<?php

namespace App\Actions\Resume\Export;

use App\Cruds\Helpers\FormHelpers;
use App\Models\ResumeExport;

class UpdateResumeExport
{
    public function __construct(
        private array $data,
        private ResumeExport $export
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);
        $allowDownload = (bool) ($data['allow_download'] ?? false);
        $name = $data['name'] ?? null;

        if ($allowDownload) {
            $this->export->user->resumeExports()
                ->where('type', $this->export->type)
                ->where('id', '!=', $this->export->id)
                ->update(['allow_download' => false]);
        }

        return $this->export->update([
            'name' => $name,
            'allow_download' => $allowDownload,
        ]);
    }
}
