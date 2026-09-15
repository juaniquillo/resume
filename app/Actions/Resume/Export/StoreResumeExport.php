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
     * @param  array{
     *  type: string,
     *  name: ?string,
     *  theme: ?string,
     *  allow_download: bool,
     *  use_custom_general_options: ?bool,
     *  theme_select: ?string,
     *  hide_phone: ?bool,
     *  hide_address: ?bool,
     *  hide_email: ?bool,
     *  hide_image: ?bool
     * }  $data
     * @param array{
     *  theme_select: ?string,
     *  hide_phone: ?bool,
     *  hide_address: ?bool,
     *  hide_email: ?bool,
     *  hide_image: ?bool
     * } $customOptions
     */
    public function handle(User $user, array $data, ?array $customOptions = null): ResumeExport
    {
        $data = FormHelpers::convertEmptyStringToNull($data);

        $type = $data['type'];
        $enumType = ResumeExportType::from($type);

        $allowDownload = (bool) ($data['allow_download'] ?? false);
        $theme = $enumType->themeable() ? ($data['theme'] ?? null) : null;
        $name = $data['name'] ?? null;
        $useCustomOptions = (bool) ($data['use_custom_general_options'] ?? false);

        if (! $useCustomOptions) {
            $customOptions = null;
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
