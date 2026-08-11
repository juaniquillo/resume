<?php

use App\Http\Controllers\AwardsController;
use App\Http\Controllers\BasicsController;
use App\Http\Controllers\BasicsLocationController;
use App\Http\Controllers\BasicsProfileController;
use App\Http\Controllers\CertificatesController;
use App\Http\Controllers\CoverLettersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\EducationCoursesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InterestsController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\Options\GeneralOptionsController;
use App\Http\Controllers\Options\SectionOrderingController;
use App\Http\Controllers\Options\SectionVisibilityController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectHighlightsController;
use App\Http\Controllers\PublicationsController;
use App\Http\Controllers\ReferencesController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ResumeOgController;
use App\Http\Controllers\ResumePublicDownloadController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\Tools\ResumeCacheController;
use App\Http\Controllers\Tools\ResumeExportController;
use App\Http\Controllers\Tools\ResumeExportDownloadController;
use App\Http\Controllers\Tools\ResumeImportController;
use App\Http\Controllers\Tools\ResumeImportDownloadController;
use App\Http\Controllers\Tools\ResumeOgManagementController;
use App\Http\Controllers\Tools\ResumePreview;
use App\Http\Controllers\Tools\ResumeResetController;
use App\Http\Controllers\VolunteersController;
use App\Http\Controllers\VolunteersHighlightsController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkHighlightsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('resume/{user:slug}', ResumeController::class)->middleware('throttle:60,1')->name('resume');
Route::get('resume/{user:slug}/og-preview', [ResumeOgController::class, 'show'])->name('resume.og.show');
Route::get('resume/{user:slug}/og-image.png', [ResumeOgController::class, 'image'])->name('resume.og.image');
Route::get('resume/download/{uuid}', ResumePublicDownloadController::class)->middleware('throttle:10,1')->name('resume.download');

Route::get('images/{uuid}', ImageController::class)->name('image.serve');

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/', DashboardController::class)->name('dashboard');

        /**
         * Basics
         */
        Route::get('basics', BasicsController::class)->name('dashboard.basics');

        Route::get('basics/locations', BasicsLocationController::class)->name('dashboard.basics.location');

        Route::get('basics/profiles', BasicsProfileController::class)->name('dashboard.basics.profiles');

        /**
         * Works
         */
        Route::get('works', WorkController::class)->name('dashboard.works');

        Route::get('works/{id}/highlights', WorkHighlightsController::class)->name('dashboard.works.highlights');

        /**
         * Volunteers
         */
        Route::get('volunteers', VolunteersController::class)->name('dashboard.volunteers');

        Route::get('volunteers/{id}/highlights', VolunteersHighlightsController::class)->name('dashboard.volunteers.highlights');

        /**
         * Education
         */
        Route::get('education', EducationController::class)->name('dashboard.education');

        Route::get('education/{id}/courses', EducationCoursesController::class)->name('dashboard.education.courses');

        /**
         * Awards
         */
        Route::get('awards', AwardsController::class)->name('dashboard.awards');

        /**
         * Certificates
         */
        Route::get('certificates', CertificatesController::class)->name('dashboard.certificates');

        /**
         * Publications
         */
        Route::get('publications', PublicationsController::class)->name('dashboard.publications');

        /**
         * Skills
         */
        Route::get('skills', SkillsController::class)->name('dashboard.skills');

        /**
         * Languages
         */
        Route::get('languages', LanguagesController::class)->name('dashboard.languages');

        /**
         * Interests
         */
        Route::get('interests', InterestsController::class)->name('dashboard.interests');

        /**
         * References
         */
        Route::get('references', ReferencesController::class)->name('dashboard.references');

        /**
         * Projects
         */
        Route::get('projects', ProjectController::class)->name('dashboard.projects');
        Route::get('projects/{id}/highlights', ProjectHighlightsController::class)->name('dashboard.projects.highlights');

        /**
         * Cover Letters
         */
        Route::get('cover-letters', CoverLettersController::class)->name('dashboard.cover-letters');

        /**
         * Tools
         */
        Route::get('resume/import', ResumeImportController::class)->name('dashboard.resume.import');
        Route::get('resume/import/{id}/download', ResumeImportDownloadController::class)->name('dashboard.resume.import.download');

        Route::get('resume/export', ResumeExportController::class)->name('dashboard.resume.export');
        Route::get('resume/export/{uuid}/download', ResumeExportDownloadController::class)->name('dashboard.resume.export.download');

        Route::get('resume/preview', ResumePreview::class)->name('dashboard.resume.preview');

        Route::get('resume/cache/clear', [ResumeCacheController::class, 'index'])->name('dashboard.resume.cache.clear');
        Route::post('resume/cache/clear', [ResumeCacheController::class, 'store'])->name('dashboard.resume.cache.store');

        Route::get('resume/og', ResumeOgManagementController::class)->name('dashboard.resume.og');

        Route::get('resume/reset', ResumeResetController::class)->name('dashboard.resume.reset');

        /**s
         * Options
         */
        Route::get('options/general', GeneralOptionsController::class)->name('dashboard.resume.general');

        Route::get('options/visibility', SectionVisibilityController::class)->name('dashboard.resume.visibility');
        Route::get('options/ordering', SectionOrderingController::class)->name('dashboard.resume.ordering');
    });

require __DIR__.'/settings.php';
