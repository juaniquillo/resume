<?php

use App\Http\Controllers\AwardsController;
use App\Http\Controllers\BasicsController;
use App\Http\Controllers\BasicsLocationController;
use App\Http\Controllers\BasicsLocationUpdateController;
use App\Http\Controllers\BasicsProfileController;
use App\Http\Controllers\BasicsUpdateController;
use App\Http\Controllers\CertificatesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\EducationCoursesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InterestsController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectHighlightsController;
use App\Http\Controllers\PublicationsController;
use App\Http\Controllers\ReferencesController;
use App\Http\Controllers\ResumeExportController;
use App\Http\Controllers\ResumeExportDownloadController;
use App\Http\Controllers\ResumeImportController;
use App\Http\Controllers\ResumeImportDownloadController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\VolunteersController;
use App\Http\Controllers\VolunteersHighlightsController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkHighlightsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/', DashboardController::class)->name('dashboard');

        /**
         * Basics
         */
        Route::get('basics', BasicsController::class)->name('dashboard.basics');
        Route::post('basics', BasicsUpdateController::class)->name('dashboard.basics.update');

        Route::get('basics/locations', BasicsLocationController::class)->name('dashboard.basics.location');
        Route::post('basics/locations', BasicsLocationUpdateController::class)->name('dashboard.basics.location.update');

        Route::get('basics/profiles', [BasicsProfileController::class, 'index'])->name('dashboard.basics.profiles');
        Route::post('basics/profiles', [BasicsProfileController::class, 'store'])->name('dashboard.basics.profiles.store');
        Route::get('basics/profiles/{id}/edit', [BasicsProfileController::class, 'edit'])->name('dashboard.basics.profiles.edit');
        Route::post('basics/profiles/{id}', [BasicsProfileController::class, 'update'])->name('dashboard.basics.profiles.update');
        Route::delete('basics/profiles/{id}', [BasicsProfileController::class, 'destroy'])->name('dashboard.basics.profiles.destroy');

        /**
         * Works
         */
        Route::get('works', [WorkController::class, 'index'])->name('dashboard.works');
        Route::post('works', [WorkController::class, 'store'])->name('dashboard.works.store');
        Route::get('works/{id}/edit', [WorkController::class, 'edit'])->name('dashboard.works.edit');
        Route::post('works/{id}', [WorkController::class, 'update'])->name('dashboard.works.update');
        Route::delete('works/{id}', [WorkController::class, 'destroy'])->name('dashboard.works.destroy');

        Route::get('works/{id}/highlights', [WorkHighlightsController::class, 'index'])->name('dashboard.works.highlights');
        Route::post('works/{id}/highlights', [WorkHighlightsController::class, 'store'])->name('dashboard.works.highlights.store');
        Route::get('works/{id}/highlights/{highlightId}/edit', [WorkHighlightsController::class, 'edit'])->name('dashboard.works.highlights.edit');
        Route::post('works/{id}/highlights/{highlightId}', [WorkHighlightsController::class, 'update'])->name('dashboard.works.highlights.update');
        Route::delete('works/{id}/highlights/{highlightId}', [WorkHighlightsController::class, 'destroy'])->name('dashboard.works.highlights.destroy');

        /**
         * Volunteers
         */
        Route::get('volunteers', [VolunteersController::class, 'index'])->name('dashboard.volunteers');
        Route::post('volunteers', [VolunteersController::class, 'store'])->name('dashboard.volunteers.store');
        Route::get('volunteers/{id}/edit', [VolunteersController::class, 'edit'])->name('dashboard.volunteers.edit');
        Route::post('volunteers/{id}', [VolunteersController::class, 'update'])->name('dashboard.volunteers.update');
        Route::delete('volunteers/{id}', [VolunteersController::class, 'destroy'])->name('dashboard.volunteers.destroy');

        Route::get('volunteers/{id}/highlights', [VolunteersHighlightsController::class, 'index'])->name('dashboard.volunteers.highlights');
        Route::post('volunteers/{id}/highlights', [VolunteersHighlightsController::class, 'store'])->name('dashboard.volunteers.highlights.store');
        Route::get('volunteers/{id}/highlights/{highlightId}/edit', [VolunteersHighlightsController::class, 'edit'])->name('dashboard.volunteers.highlights.edit');
        Route::post('volunteers/{id}/highlights/{highlightId}', [VolunteersHighlightsController::class, 'update'])->name('dashboard.volunteers.highlights.update');
        Route::delete('volunteers/{id}/highlights/{highlightId}', [VolunteersHighlightsController::class, 'destroy'])->name('dashboard.volunteers.highlights.destroy');

        /**
         * Education
         */
        Route::get('education', [EducationController::class, 'index'])->name('dashboard.education');
        Route::post('education', [EducationController::class, 'store'])->name('dashboard.education.store');
        Route::get('education/{id}/edit', [EducationController::class, 'edit'])->name('dashboard.education.edit');
        Route::post('education/{id}', [EducationController::class, 'update'])->name('dashboard.education.update');
        Route::delete('education/{id}', [EducationController::class, 'destroy'])->name('dashboard.education.destroy');

        Route::get('education/{id}/courses', [EducationCoursesController::class, 'index'])->name('dashboard.education.courses');
        Route::post('education/{id}/courses', [EducationCoursesController::class, 'store'])->name('dashboard.education.courses.store');
        Route::get('education/{id}/courses/{courseId}/edit', [EducationCoursesController::class, 'edit'])->name('dashboard.education.courses.edit');
        Route::post('education/{id}/courses/{courseId}', [EducationCoursesController::class, 'update'])->name('dashboard.education.courses.update');
        Route::delete('education/{id}/courses/{courseId}', [EducationCoursesController::class, 'destroy'])->name('dashboard.education.courses.destroy');

        /**
         * Awards
         */
        Route::get('awards', [AwardsController::class, 'index'])->name('dashboard.awards');
        Route::post('awards', [AwardsController::class, 'store'])->name('dashboard.awards.store');
        Route::get('awards/{id}/edit', [AwardsController::class, 'edit'])->name('dashboard.awards.edit');
        Route::post('awards/{id}', [AwardsController::class, 'update'])->name('dashboard.awards.update');
        Route::delete('awards/{id}', [AwardsController::class, 'destroy'])->name('dashboard.awards.destroy');

        /**
         * Certificates
         */
        Route::get('certificates', [CertificatesController::class, 'index'])->name('dashboard.certificates');
        Route::post('certificates', [CertificatesController::class, 'store'])->name('dashboard.certificates.store');
        Route::get('certificates/{id}/edit', [CertificatesController::class, 'edit'])->name('dashboard.certificates.edit');
        Route::post('certificates/{id}', [CertificatesController::class, 'update'])->name('dashboard.certificates.update');
        Route::delete('certificates/{id}', [CertificatesController::class, 'destroy'])->name('dashboard.certificates.destroy');

        /**
         * Publications
         */
        Route::get('publications', [PublicationsController::class, 'index'])->name('dashboard.publications');
        Route::post('publications', [PublicationsController::class, 'store'])->name('dashboard.publications.store');
        Route::get('publications/{id}/edit', [PublicationsController::class, 'edit'])->name('dashboard.publications.edit');
        Route::post('publications/{id}', [PublicationsController::class, 'update'])->name('dashboard.publications.update');
        Route::delete('publications/{id}', [PublicationsController::class, 'destroy'])->name('dashboard.publications.destroy');

        /**
         * Skills
         */
        Route::get('skills', [SkillsController::class, 'index'])->name('dashboard.skills');
        Route::post('skills', [SkillsController::class, 'store'])->name('dashboard.skills.store');
        Route::get('skills/{id}/edit', [SkillsController::class, 'edit'])->name('dashboard.skills.edit');
        Route::post('skills/{id}', [SkillsController::class, 'update'])->name('dashboard.skills.update');
        Route::delete('skills/{id}', [SkillsController::class, 'destroy'])->name('dashboard.skills.destroy');

        /**
         * Languages
         */
        Route::get('languages', [LanguagesController::class, 'index'])->name('dashboard.languages');
        Route::post('languages', [LanguagesController::class, 'store'])->name('dashboard.languages.store');
        Route::get('languages/{id}/edit', [LanguagesController::class, 'edit'])->name('dashboard.languages.edit');
        Route::post('languages/{id}', [LanguagesController::class, 'update'])->name('dashboard.languages.update');
        Route::delete('languages/{id}', [LanguagesController::class, 'destroy'])->name('dashboard.languages.destroy');

        /**
         * Interests
         */
        Route::get('interests', [InterestsController::class, 'index'])->name('dashboard.interests');
        Route::post('interests', [InterestsController::class, 'store'])->name('dashboard.interests.store');
        Route::get('interests/{id}/edit', [InterestsController::class, 'edit'])->name('dashboard.interests.edit');
        Route::post('interests/{id}', [InterestsController::class, 'update'])->name('dashboard.interests.update');
        Route::delete('interests/{id}', [InterestsController::class, 'destroy'])->name('dashboard.interests.destroy');

        /**
         * References
         */
        Route::get('references', [ReferencesController::class, 'index'])->name('dashboard.references');
        Route::post('references', [ReferencesController::class, 'store'])->name('dashboard.references.store');
        Route::get('references/{id}/edit', [ReferencesController::class, 'edit'])->name('dashboard.references.edit');
        Route::post('references/{id}', [ReferencesController::class, 'update'])->name('dashboard.references.update');
        Route::delete('references/{id}', [ReferencesController::class, 'destroy'])->name('dashboard.references.destroy');

        /**
         * Projects
         */
        Route::get('projects', [ProjectController::class, 'index'])->name('dashboard.projects');
        Route::post('projects', [ProjectController::class, 'store'])->name('dashboard.projects.store');
        Route::get('projects/{id}/edit', [ProjectController::class, 'edit'])->name('dashboard.projects.edit');
        Route::post('projects/{id}', [ProjectController::class, 'update'])->name('dashboard.projects.update');
        Route::delete('projects/{id}', [ProjectController::class, 'destroy'])->name('dashboard.projects.destroy');

        Route::get('projects/{id}/highlights', [ProjectHighlightsController::class, 'index'])->name('dashboard.projects.highlights');
        Route::post('projects/{id}/highlights', [ProjectHighlightsController::class, 'store'])->name('dashboard.projects.highlights.store');
        Route::get('projects/{id}/highlights/{highlightId}/edit', [ProjectHighlightsController::class, 'edit'])->name('dashboard.projects.highlights.edit');
        Route::post('projects/{id}/highlights/{highlightId}', [ProjectHighlightsController::class, 'update'])->name('dashboard.projects.highlights.update');
        Route::delete('projects/{id}/highlights/{highlightId}', [ProjectHighlightsController::class, 'destroy'])->name('dashboard.projects.highlights.destroy');

        /**
         * Tools
         */
        Route::get('resume/import', [ResumeImportController::class, 'index'])->name('dashboard.resume.import');
        Route::post('resume/import', [ResumeImportController::class, 'store'])->name('dashboard.resume.import.store');
        Route::delete('resume/import/{id}', [ResumeImportController::class, 'destroy'])->name('dashboard.resume.import.destroy');
        Route::get('resume/import/{id}/download', ResumeImportDownloadController::class)->name('dashboard.resume.import.download');

        Route::get('resume/export', [ResumeExportController::class, 'index'])->name('dashboard.resume.export');
        Route::post('resume/export', [ResumeExportController::class, 'store'])->name('dashboard.resume.export.store');
        Route::delete('resume/export/{id}', [ResumeExportController::class, 'destroy'])->name('dashboard.resume.export.destroy');

        Route::get('resume/export/{uuid}/download', ResumeExportDownloadController::class)->name('dashboard.resume.export.download');

    });

Route::get('images/{uuid}', ImageController::class)->name('image.serve');

require __DIR__.'/settings.php';
