<?php

use App\Enums\Network;
use App\Models\Award;
use App\Models\Basic;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Education;
use App\Models\Interest;
use App\Models\Language;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Reference;
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
    $basic = Basic::factory()->for($user)->create([
        'name' => 'John Doe',
        'label' => 'Software Engineer',
        'summary' => "I am a software engineer.\nLine 2 of summary.",
    ]);

    $basic->location()->create([
        'address' => '123 Main St',
        'city' => 'Tech City',
        'region' => 'Silicon Valley',
        'postal_code' => '12345',
        'country_code' => 'US',
    ]);

    // Create profile
    Profile::factory()->create([
        'basic_id' => $basic->id,
        'network' => Network::GITHUB,
        'username' => 'johndoe_git',
        'url' => 'https://github.com/johndoe_git',
    ]);

    // Create work
    Work::factory()->for($user)->create([
        'position' => 'Developer',
        'name' => 'Company Inc',
        'url' => 'https://company.inc',
        'starts_at' => now()->subYear(),
        'ends_at' => now(),
    ]);

    // Create volunteer
    Volunteer::factory()->for($user)->create([
        'position' => 'Volunteer Developer',
        'organization' => 'Open Source Foundation',
        'url' => 'https://osf.org',
        'starts_at' => now()->subYear(),
    ]);

    // Create education
    $education = Education::factory()->for($user)->create([
        'institution' => 'University of Tech',
        'area' => 'Computer Science',
        'study_type' => 'Bachelor',
        'score' => '4.0 GPA',
        'url' => 'https://u-tech.edu',
        'starts_at' => now()->subYears(5),
        'ends_at' => now()->subYear(),
    ]);

    // Create course
    Course::factory()->create([
        'courseable_type' => Education::class,
        'courseable_id' => $education->id,
        'course' => 'Advanced PHP',
    ]);

    // Create award
    Award::factory()->for($user)->create([
        'title' => 'Best Developer',
        'awarder' => 'Tech Academy',
        'awarded_at' => now()->subMonths(6),
    ]);

    // Create certificate
    Certificate::factory()->for($user)->create([
        'name' => 'Laravel Certified',
        'date' => now()->subMonths(3),
    ]);

    // Create publication
    Publication::factory()->for($user)->create([
        'name' => 'How to build a resume',
        'issuer' => 'Dev Blog',
        'date' => now()->subMonth(),
    ]);

    // Create skill
    Skill::factory()->for($user)->create([
        'name' => 'PHP',
        'level' => 'Senior',
        'keywords' => ['Laravel', 'Symfony'],
    ]);

    // Create language
    Language::factory()->for($user)->create([
        'language' => 'English',
        'fluency' => 'Native',
    ]);

    // Create interest
    Interest::factory()->for($user)->create([
        'name' => 'Gaming',
        'keywords' => ['RPG', 'FPS'],
    ]);

    // Create reference
    Reference::factory()->for($user)->create([
        'name' => 'Jane Smith',
        'reference' => 'He is a great developer.',
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
    expect($html)->toContain('123 Main St, Tech City, Silicon Valley, 12345, US');
    expect($html)->toContain('Developer');
    expect($html)->toContain('Company Inc');
    expect($html)->toContain('https://company.inc');
    expect($html)->toContain('University of Tech');
    expect($html)->toContain('4.0 GPA');
    expect($html)->toContain('https://u-tech.edu');
    expect($html)->toContain('PHP');
    expect($html)->toContain('Senior');
    expect($html)->toContain('Laravel');
    expect($html)->toContain('Symfony');
    expect($html)->toContain('English');
    expect($html)->toContain('Resume Project');
    expect($html)->toContain('Volunteer Developer');
    expect($html)->toContain('Open Source Foundation');
    expect($html)->toContain('https://osf.org');
    expect($html)->toContain('Advanced PHP');
    expect($html)->toContain('Best Developer');
    expect($html)->toContain('Laravel Certified');
    expect($html)->toContain('How to build a resume');
    expect($html)->toContain('Gaming');
    expect($html)->toContain('Jane Smith');
    expect($html)->toContain('Github');
    expect($html)->toContain('https://github.com/johndoe_git');
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

        public function basicsContainerThemes(): array
        {
            return [];
        }

        public function summaryContainerThemes(): array
        {
            return [];
        }

        public function workContainerThemes(): array
        {
            return [];
        }

        public function volunteersContainerThemes(): array
        {
            return [];
        }

        public function educationContainerThemes(): array
        {
            return [];
        }

        public function awardsContainerThemes(): array
        {
            return [];
        }

        public function certificatesContainerThemes(): array
        {
            return [];
        }

        public function publicationsContainerThemes(): array
        {
            return [];
        }

        public function skillsContainerThemes(): array
        {
            return [];
        }

        public function languagesContainerThemes(): array
        {
            return [];
        }

        public function interestsContainerThemes(): array
        {
            return [];
        }

        public function referencesContainerThemes(): array
        {
            return [];
        }

        public function projectsContainerThemes(): array
        {
            return [];
        }

        public function downloadsContainerThemes(): array
        {
            return [];
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

        public function imageContainerThemes(): array
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

        public function iconThemes(): array
        {
            return [];
        }

        public function listItemThemes(): array
        {
            return [];
        }

        public function badgeThemes(): array
        {
            return [];
        }

        public function keywordBadgeThemes(): array
        {
            return [];
        }

        public function socialBadgeThemes(): array
        {
            return [];
        }

        public function dateThemes(): array
        {
            return [];
        }

        public function subTitleThemes(): array
        {
            return [];
        }

        public function emailThemes(): array
        {
            return [];
        }

        public function phoneThemes(): array
        {
            return [];
        }

        public function urlThemes(): array
        {
            return [];
        }

        public function locationThemes(): array
        {
            return [];
        }

        public function profileThemes(): array
        {
            return [];
        }

        public function coverLetterContainerThemes(): array
        {
            return [];
        }

        public function coverLetterThemes(): array
        {
            return [];
        }

        public function fontUrls(): array
        {
            return [];
        }

        public function localFonts(): array
        {
            return [];
        }

        public function fontFamily(): string
        {
            return 'sans-serif';
        }
    };

    $presenter = new ResumePresenter($user, $customTheme);
    $html = (string) $presenter->present()->toHtml();

    // 'p-xs' is defined in spacing.blade.php as 'p-2'
    expect($html)->toContain('p-2');
    // 'm-bottom-xs' is defined in spacing.blade.php as 'mb-1'
    expect($html)->toContain('mb-1');
});

test('it respects section visibility settings', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create([
        'name' => 'John Doe',
        'summary' => 'My summary',
    ]);
    Work::factory()->for($user)->create([
        'name' => 'Company Inc',
    ]);

    // Initially everything is visible
    $presenter = new ResumePresenter($user);
    $html = (string) $presenter->present()->toHtml();
    expect($html)->toContain('My summary');
    expect($html)->toContain('Company Inc');

    // Hide summary and work
    $user->sectionVisibility()->create([
        'settings' => [
            'summary' => true, // true = disabled
            'work' => true,
        ],
    ]);

    $user->refresh();
    $presenter = new ResumePresenter($user);
    $html = (string) $presenter->present()->toHtml();

    expect($html)->not->toContain('My summary');
    expect($html)->not->toContain('Company Inc');
    expect($html)->toContain('John Doe'); // Basics always visible
});
