<?php

use App\Enums\ResumeTheme;
use App\Support\Showcase\ShowcaseFixture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelScreenshot\Facades\Screenshot;

uses(RefreshDatabase::class);

pest()->group('fast');

beforeEach(function () {
    Storage::fake('local');
});

test('showcase fixture seeds every section with a photo', function () {
    $user = ShowcaseFixture::seed();

    expect(ShowcaseFixture::isSeeded())->toBeTrue();
    expect($user->resumeBasics())->not->toBeNull();
    expect($user->works()->count())->toBe(2);
    expect($user->volunteers()->count())->toBe(1);
    expect($user->awards()->count())->toBe(1);
    expect($user->certificates()->count())->toBe(1);
    expect($user->publications()->count())->toBe(1);
    expect($user->projects()->count())->toBe(1);
    expect($user->references()->count())->toBe(1);
    expect($user->interests()->count())->toBe(2);

    $image = $user->resumeBasics()->image;
    expect($image)->toBeString()->not->toBeEmpty();
    expect($image)->toStartWith('basics/');
    Storage::disk(config('filesystems.default'))->assertExists($image);
});

test('showcase preview renders the fixture with the requested theme', function () {
    ShowcaseFixture::seed();

    $this->get(route('showcase.preview', 'default'))
        ->assertOk()
        ->assertSee('User Test', false)
        ->assertSee('scroll-smooth light', false)
        // The component must render as HTML, never as its JSON tree dump.
        ->assertSee('<h1', false)
        ->assertDontSee('"component":"Juaniquillo', false);

    $this->get(route('showcase.preview', ['theme' => 'default', 'dark' => 1]))
        ->assertOk()
        ->assertSee('scroll-smooth dark', false)
        ->assertSee('<h1', false)
        ->assertDontSee('"component":"Juaniquillo', false);
});

test('showcase preview rejects hidden and unknown themes', function () {
    ShowcaseFixture::seed();

    $this->get(route('showcase.preview', 'blank'))->assertNotFound();
    $this->get(route('showcase.preview', 'pdf'))->assertNotFound();
    $this->get(route('showcase.preview', 'nope'))->assertNotFound();
});

test('showcase capture saves light and dark shots per showcased theme', function () {
    Screenshot::fake();

    $this->artisan('showcase:capture')->assertSuccessful();

    foreach (ResumeTheme::showcase() as $theme) {
        Screenshot::assertSaved(public_path("images/themes/{$theme->value}.png"));
        Screenshot::assertSaved(public_path("images/themes/{$theme->value}-dark.png"));
    }
});

test('showcase capture accepts a single theme including hidden ones', function () {
    Screenshot::fake();

    $this->artisan('showcase:capture', ['theme' => 'modern'])->assertSuccessful();

    Screenshot::assertSaved(public_path('images/themes/modern.png'));
    Screenshot::assertSaved(public_path('images/themes/modern-dark.png'));
});
