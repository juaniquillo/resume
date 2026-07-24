<?php

namespace App\Actions\Resume\Export;

use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Models\ResumeExport;
use App\Models\User;

class CreateResumeExport
{
    /**
     * @param  array{type: string, theme: ?string, allow_download: bool}  $data
     */
    public function handle(User $user, array $data): ResumeExport
    {
        $type = $data['type'];
        $enumType = ResumeExportType::from($type);

        // Force allow_download to false and theme to null if not PDF
        $allowDownload = (bool) $data['allow_download'];
        $theme = $enumType->themeable() ? ($data['theme'] ?? null) : null;

        if ($allowDownload) {
            // Unmark any other export of the same type currently allowed for download
            $user->resumeExports()
                ->where('type', $type)
                ->update(['allow_download' => false]);
        }

        /** @var ResumeExport $export */
        $export = $user->resumeExports()->create([
            'status' => ProcessStatus::PENDING,
            'type' => $type,
            'theme' => $theme,
            'allow_download' => $allowDownload,
        ]);

        return $export;
    }
}



