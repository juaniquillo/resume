<?php

use App\Actions\Resume\UpdateSectionOrder;
use App\Enums\ResumeSection;
use App\Livewire\Options\SectionOrdering;
use App\Models\Award;
use App\Models\Basic;
use App\Models\Skill;
use App\Models\User;
use App\Presenters\ResumePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

uses(RefreshDatabase::class);

pest()->group('slow');

test('authenticated user can view section ordering page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.resume.ordering'))
        ->assertOk()
        ->assertViewIs('dashboard.options.ordering')
        ->assertSee('Section Ordering');
});

test('it initializes section orders if they do not exist', function () {
    $user = User::factory()->create();

    // In Livewire 4, computed properties are accessed on the component instance
    $component = Livewire::actingAs($user)
        ->test(SectionOrdering::class);

    expect($component->instance()->sections)->not->toBeEmpty();

    // The new self-healing logic initializes them automatically on load
    expect($user->sectionOrders()->count())->toBe(count(ResumeSection::cases()));
});

test('moving a section updates the database', function () {
    $user = User::factory()->create();

    // Seed default orders
    $action = new UpdateSectionOrder;
    $action->handle($user, ResumeSection::WORK->value, 1);

    $originalOrder = $user->sectionOrders()->orderBy('sort_order')->pluck('section')->toArray();
    $target = ResumeSection::SKILLS->value;

    // Move SKILLS to the very top (index 0)
    Livewire::actingAs($user)
        ->test(SectionOrdering::class)
        ->call('handleSort', $target, 0);

    $newOrder = $user->sectionOrders()->orderBy('sort_order')->pluck('section')->toArray();

    expect($newOrder[0])->toBe($target);
    expect($newOrder)->not->toBe($originalOrder);
});

test('ResumePresenter respects custom order', function () {
    $user = User::factory()->create();
    Basic::factory()->create(['user_id' => $user->id, 'name' => 'John Doe']);
    Skill::factory()->create(['user_id' => $user->id, 'name' => 'PHP']);
    Award::factory()->create(['user_id' => $user->id, 'title' => 'Best Dev']);

    // Move SKILLS to index 0, AWARDS to index 1
    $action = new UpdateSectionOrder;
    $action->handle($user, ResumeSection::SKILLS->value, 0);
    $action->handle($user, ResumeSection::AWARDS->value, 1);

    $presenter = new ResumePresenter($user);
    $html = (string) $presenter->present()->toHtml();

    // Check positions in HTML
    $skillsPos = strpos($html, 'Skills');
    $awardsPos = strpos($html, 'Awards');
    $basicsPos = strpos($html, 'John Doe');

    expect($basicsPos)->toBeLessThan($skillsPos);
    expect($skillsPos)->toBeLessThan($awardsPos);
});

test('changing section order invalidates resume cache', function () {
    $user = User::factory()->create();
    $presenter = new ResumePresenter($user);
    $cacheKey = $presenter->getCacheKey();

    Cache::forever($cacheKey, 'cached_content');

    Livewire::actingAs($user)
        ->test(SectionOrdering::class)
        ->call('handleSort', ResumeSection::WORK->value, 0);

    expect(Cache::has($cacheKey))->toBeFalse();
});
