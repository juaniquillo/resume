<?php

namespace App\Presenters\Resume\Concerns;

use App\Presenters\Resume\ResumeThemeManager;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;

trait HasThemeManager
{
    private ?ThemeManager $themeManager = null;
    
    public function getThemeManager(): ThemeManager
    {
        if ($this->themeManager) {
            return $this->themeManager;
        }

        return new ResumeThemeManager;
    }

    public function setThemeManager(?ThemeManager $themeManager = null): static
    {
        $this->themeManager = $themeManager;

        return $this;
    }
}
