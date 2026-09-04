<?php

namespace App\Actions\Resume\Export;

use App\Cruds\Helpers\FormHelpers;
use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Models\ResumeExport;
use App\Models\User;

class StoreResumeExport
{
    /**
     * @param  array{type: string, name: ?string, theme: ?string, allow_download: bool, use_custom_general_options: ?bool, theme_select: ?string, hide_phone: ?bool, hide_address: ?bool, hide_email: ?bool, hide_image: ?bool}  $data
     */
    public function handle(User $user, array $data): ResumeExport
    {
        $data = FormHelpers::convertEmptyStringToNull($data);

        $type = $data['type'];
        $enumType = ResumeExportType::from($type);

        $allowDownload = (bool) ($data['allow_download'] ?? false);
        $theme = $enumType->themeable() ? ($data['theme'] ?? null) : null;
        $name = $data['name'] ?? null;
        $useCustomOptions = (bool) ($data['use_custom_general_options'] ?? false);

        $customOptions = null;
        if ($useCustomOptions) {
            $customOptions = [
                'theme_select' => $data['theme_select'] ?? null,
                'hide_phone' => (bool) ($data['hide_phone'] ?? false),
                'hide_address' => (bool) ($data['hide_address'] ?? false),
                'hide_email' => (bool) ($data['hide_email'] ?? false),
                'hide_image' => (bool) ($data['hide_image'] ?? false),
            ];
        }

        if ($allowDownload) {
            $user->resumeExports()
                ->where('type', $enumType)
                ->update(['allow_download' => false]);
        }

        /** @var ResumeExport $export */
        $export = $user->resumeExports()->create([
            'status' => ProcessStatus::PENDING,
            'name' => $name,
            'type' => $enumType,
            'theme' => $theme,
            'allow_download' => $allowDownload,
            'custom_options' => $customOptions,
        ]);

        return $export;
    }
}
