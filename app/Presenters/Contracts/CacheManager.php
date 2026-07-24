<?php

namespace App\Presenters\Contracts;

use App\Presenters\ResumePresenter;

interface CacheManager
{
    public function present(ResumePresenter $presenter): string;

    public function cachePresenter(string $key, string $html): string;

    public function getCacheKey(): string;

    public function clearCache(): void;
}
