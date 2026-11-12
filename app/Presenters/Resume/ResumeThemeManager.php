<?php

namespace App\Presenters\Resume;

use Juaniquillo\BaseBackendComponent\Concerns\IsThemeManager;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeManager;

class ResumeThemeManager implements ThemeManager
{
    use IsThemeManager;

    public function __construct()
    {
        $this->setDefaultPath(resource_path('views/_themes/tailwind/resume/'));
        $this->setThemeExtension('.blade.php');
    }
}
