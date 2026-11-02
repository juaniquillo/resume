<?php

use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;
use App\Actions\Resume\Export\Markdown\ResumeMarkdownRenderer;
use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Enums\ResumeSection;
use App\Jobs\ProcessMarkdownExport;
use App\Livewire\Resume\Export\CreateResumeExport;
use App\Models\Basic;
use App\Models\ResumeExport;
use App\Models\SectionOrder;
use App\Models\SectionVisibility;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('markdown renderer renders the resume fixture in the given order', function () {
    $resume = json_decode(
        file_get_contents(base_path('storage/tests/user-test-resume.json')),
        true,
    );

    $markdown = (new ResumeMarkdownRenderer)->render($resume, [
        'projects', 'work', 'skills', 'summary',
    ]);

    expect($markdown)->toStartWith('# User Test');
    expect($markdown)->toContain('## Summary');
    expect($markdown)->toContain('## Experience');
    expect($markdown)->toContain('## Projects');
    expect($markdown)->toContain('## Skills');

    $projectsPos = strpos($markdown, '## Projects');
    $workPos = strpos($markdown, '## Experience');
    $skillsPos = strpos($markdown, '## Skills');

    expect($projectsPos)->toBeLessThan($workPos);
    expect($workPos)->toBeLessThan($skillsPos);

    // Contact links and highlights render as Markdown, not raw data.
    expect($markdown)->toContain('[user-test@test.com](mailto:user-test@test.com)');
    expect($markdown)->toContain('[LinkedIn (@janedoe)](https://linkedin.com)');
    expect($markdown)->toContain('- Reduced server response latency by 40% via caching layers.');
});

test('the background job generates a markdown file', function () {
    Storage::fake('local');

    Basic::factory()->create(['user_id' => $this->user->id]);
    Work::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Tech Corp',
        'starts_at' => '2023-02-01',
        'ends_at' => '2024-06-01',
    ]);

    $export = ResumeExport::create([
        'user_id' => $this->user->id,
        'status' => ProcessStatus::PENDING,
        'type' => ResumeExportType::MARKDOWN->value,
    ]);

    (new ProcessMarkdownExport($export))->handle();

    $export->refresh();
    expect($export->status)->toBe(ProcessStatus::COMPLETED);
    expect($export->file_path)->not->toBeNull();
    expect($export->file_path)->toEndWith('.md');

    Storage::disk('local')->assertExists($export->file_path);

    $markdown = Storage::disk('local')->get($export->file_path);
    expect($markdown)->toStartWith('# ');
    expect($markdown)->toContain('## Experience');
    expect($markdown)->toContain('Tech Corp');
    // Live builder output hands dates over as Carbon, not strings.
    expect($markdown)->toContain('Feb 2023 – Jun 2024');
    expect($markdown)->not->toContain('Undated');
});

test('markdown exports respect visibility and ordering', function () {
    Storage::fake('local');

    Basic::factory()->create(['user_id' => $this->user->id]);
    Work::factory()->create(['user_id' => $this->user->id, 'name' => 'Tech Corp']);

    SectionVisibility::create([
        'user_id' => $this->user->id,
        'settings' => ['skills' => true, 'summary' => true],
    ]);

    SectionOrder::create(['user_id' => $this->user->id, 'section' => 'projects', 'sort_order' => 0]);
    SectionOrder::create(['user_id' => $this->user->id, 'section' => 'work', 'sort_order' => 1]);
    SectionOrder::create(['user_id' => $this->user->id, 'section' => 'skills', 'sort_order' => 2]);

    $export = ResumeExport::create([
        'user_id' => $this->user->id,
        'status' => ProcessStatus::PENDING,
        'type' => ResumeExportType::MARKDOWN->value,
    ]);

    (new ProcessMarkdownExport($export))->handle();

    $markdown = Storage::disk('local')->get($export->fresh()->file_path);

    expect($markdown)->not->toContain('## Summary');
    expect($markdown)->not->toContain('## Skills');
    expect(strpos($markdown, '## Projects'))->toBeLessThan(strpos($markdown, '## Experience'));
});

test('it can initiate a markdown export', function () {
    Queue::fake();

    Livewire::actingAs($this->user)
        ->test(CreateResumeExport::class)
        ->set('resumeExport.name', 'My Markdown Export')
        ->set('resumeExport.type', ResumeExportType::MARKDOWN->value)
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('resume_exports', [
        'user_id' => $this->user->id,
        'status' => ProcessStatus::PENDING,
        'type' => ResumeExportType::MARKDOWN->value,
    ]);

    Queue::assertPushed(ProcessMarkdownExport::class);
});

test('markdown exports can be downloaded by owner and guests', function () {
    Storage::fake('local');
    $filePath = 'exports/resumes/test-export.md';
    Storage::disk('local')->put($filePath, '# Test');

    $export = ResumeExport::create([
        'user_id' => $this->user->id,
        'status' => ProcessStatus::COMPLETED,
        'file_path' => $filePath,
        'type' => ResumeExportType::MARKDOWN->value,
        'allow_download' => true,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.resume.export.download', $export->uuid))
        ->assertSuccessful();

    $this->get(route('resume.download', $export->uuid))
        ->assertSuccessful();
});

test('markdown filenames use the md extension', function () {
    Basic::factory()->create(['user_id' => $this->user->id, 'name' => 'Jane Doe']);

    $export = ResumeExport::create([
        'user_id' => $this->user->id,
        'status' => ProcessStatus::COMPLETED,
        'file_path' => 'exports/resumes/test.md',
        'type' => ResumeExportType::MARKDOWN->value,
    ]);

    expect(ResumeExportType::MARKDOWN->filename($export))->toBe('jane-doe-resume.md');
    expect(ResumeExportType::MARKDOWN->themeable())->toBeFalse();
});

test('every displayable resume section has a markdown renderer', function () {
    $renderers = collect(glob(app_path('Actions/Resume/Export/Markdown/Sections/*.php')))
        ->map(fn ($path) => 'App\\Actions\\Resume\\Export\\Markdown\\Sections\\'.basename($path, '.php'))
        ->filter(fn ($class) => is_subclass_of($class, MarkdownSectionRenderer::class))
        ->map(fn ($class) => (new $class)->section())
        ->sort()
        ->values()
        ->all();

    $expected = collect(ResumeSection::cases())
        ->pluck('value')
        ->reject(fn ($value) => $value === ResumeSection::DOWNLOADS->value)
        ->push('basics')
        ->sort()
        ->values()
        ->all();

    expect($renderers)->toEqual($expected);
});

test('the markdown job fails without basics', function () {
    Storage::fake('local');

    $export = ResumeExport::create([
        'user_id' => $this->user->id,
        'status' => ProcessStatus::PENDING,
        'type' => ResumeExportType::MARKDOWN->value,
    ]);

    (new ProcessMarkdownExport($export))->handle();

    $export->refresh();
    expect($export->status)->toBe(ProcessStatus::FAILED);
    expect($export->file_path)->toBeNull();
});
