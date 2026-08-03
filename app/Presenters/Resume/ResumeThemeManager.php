<?php

namespace App\Presenters\Resume;

use Juaniquillo\BackendComponents\Concerns\IsThemeManager;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;

class ResumeThemeManager implements ThemeManager
{
    use IsThemeManager;

    public function __construct()
    {
        $this->setDefaultPath(resource_path('views/_themes/tailwind/resume/'));
    }
}
