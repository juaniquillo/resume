<?php

use App\Enums\ProcessStatus;
use App\Enums\ResumeExportType;
use App\Models\Basic;
use App\Models\ResumeExport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

pest()->group('slow');

test('public resume route is accessible by slug', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'test-slug', 'is_draft' => false]);
    Basic::factory()->create(['user_id' => $user->id, 'name' => 'Test User']);

    $response = $this->get(route('resume', ['user' => 'test-slug']));

    $response->assertStatus(200);
    $response->assertViewIs('pages.resume');
    $response->assertSee('Test User');
});

test('resume route returns 404 for non-existent slug', function () {
    $response = $this->get('/resume/non-existent-slug');

    $response->assertStatus(404);
});

test('resume is unavailable for public when draft', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'draft-slug', 'is_draft' => true]);
    Basic::factory()->create(['user_id' => $user->id, 'name' => 'Draft User']);

    $response = $this->get(route('resume', ['user' => 'draft-slug']));

    $response->assertStatus(403);
    $response->assertViewIs('pages.resume-draft');
    $response->assertSee('Resume Unavailable');
});

test('public resume shows allowed downloads', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'download-slug', 'is_draft' => false]);
    Basic::factory()->create(['user_id' => $user->id]);

    ResumeExport::create([
        'user_id' => $user->id,
        'type' => ResumeExportType::JSON,
        'status' => ProcessStatus::COMPLETED,
        'file_path' => 'path.json',
        'allow_download' => true,
    ]);

    $response = $this->get(route('resume', 'download-slug'));

    $response->assertStatus(200);
    $response->assertSee('Downloads');
    $response->assertSee('JSON Format');
});
