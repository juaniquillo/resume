<?php

use App\Models\Basic;
use App\Models\Education;
use App\Models\Language;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Work;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\ResumePresenter;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;

test('it can present a resume for a user', function () {
    $user = User::factory()->create();

    // Create basics
    Basic::factory()->for($user)->create([
        'name' => 'John Doe',
        'label' => 'Software Engineer',
        'summary' => "I am a software engineer.\nLine 2 of summary.",
    ]);

    // Create work
    Work::factory()->for($user)->create([
        'position' => 'Developer',
        'name' => 'Company Inc',
        'starts_at' => now()->subYear(),
        'ends_at' => now(),
    ]);

    // Create volunteer
    Volunteer::factory()->for($user)->create([
        'position' => 'Volunteer Developer',
        'organization' => 'Open Source Foundation',
        'starts_at' => now()->subYear(),
    ]);

    // Create education
    Education::factory()->for($user)->create([
        'institution' => 'University of Tech',
        'area' => 'Computer Science',
        'study_type' => 'Bachelor',
        'starts_at' => now()->subYears(5),
        'ends_at' => now()->subYear(),
    ]);

    // Create skill
    Skill::factory()->for($user)->create([
        'name' => 'PHP',
    ]);

    // Create language
    Language::factory()->for($user)->create([
        'language' => 'English',
        'fluency' => 'Native',
    ]);

    // Create project
    Project::factory()->for($user)->create([
        'name' => 'Resume Project',
        'description' => 'A project for resumes.',
        'start_date' => now()->subMonths(6),
    ]);

    $presenter = new ResumePresenter($user);
    $component = $presenter->present();

    expect($component)->toBeInstanceOf(BackendComponent::class);

    $html = (string) $component->toHtml();

    expect($html)->toContain('John Doe');
    expect($html)->toContain('Software Engineer');
    expect($html)->toContain('I am a software engineer.');
    expect($html)->toContain('Developer');
    expect($html)->toContain('Company Inc');
    expect($html)->toContain('University of Tech');
    expect($html)->toContain('PHP');
    expect($html)->toContain('English');
    expect($html)->toContain('Resume Project');
    expect($html)->toContain('Volunteer Developer');
    expect($html)->toContain('Open Source Foundation');
});

test('it handles missing data gracefully', function () {
    $user = User::factory()->create();

    $presenter = new ResumePresenter($user);
    $component = $presenter->present();

    expect($component)->toBeInstanceOf(BackendComponent::class);

    $html = (string) $component->toHtml();

    // Should not contain section headers if data is missing
    expect($html)->not->toContain('Summary');
    expect($html)->not->toContain('Experience');
    expect($html)->not->toContain('Education');
});

test('it can use a custom theme', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create(['name' => 'Themed User']);

    $customTheme = new class implements PresenterTheme
    {
        public function containerThemes(): array
        {
            return ['spacing' => 'p-xs'];
        }

        public function nameThemes(): array
        {
            return [];
        }

        public function labelThemes(): array
        {
            return [];
        }

        public function sectionThemes(): array
        {
            return ['spacing' => 'm-bottom-xs'];
        }

        public function sectionTitleThemes(): array
        {
            return [];
        }

        public function itemTitleThemes(): array
        {
            return [];
        }

        public function itemContainerThemes(): array
        {
            return [];
        }

        public function itemDetailsThemes(): array
        {
            return [];
        }

        public function summaryThemes(): array
        {
            return [];
        }

        public function contactContainerThemes(): array
        {
            return [];
        }

        public function contactItemThemes(): array
        {
            return [];
        }

        public function listThemes(): array
        {
            return [];
        }

        public function imageThemes(): array
        {
            return [];
        }

        public function linkThemes(): array
        {
            return [];
        }
    };

    $presenter = new ResumePresenter($user, $customTheme);
    $html = (string) $presenter->present()->toHtml();

    // 'p-xs' is defined in spacing.blade.php as 'p-2'
    expect($html)->toContain('p-2');
    // 'm-bottom-xs' is defined in spacing.blade.php as 'mb-1'
    expect($html)->toContain('mb-1');
});
