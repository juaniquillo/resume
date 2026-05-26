<?php

namespace App\Providers;

use App\Presenters\Cache\ResumeThemeCacheManager;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Blaze\Blaze;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->resisterClasses();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureBlaze();
    }

    public function resisterClasses()
    {
        $this->app->singleton(ResumeThemeCacheManager::class, function (Application $app) {
            return new ResumeThemeCacheManager;
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Model::automaticallyEagerLoadRelationships();

        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    public function configureBlaze(): void
    {
        $path = base_path('vendor/juaniquillo/laravel-backend-component/resources/views/components');
        Blaze::optimize()->in(resource_path($path));
    }
}
