<?php

use App\Livewire\Resume\Works\CreateWork;
use App\Models\User;
use App\Models\Work;
use App\Support\ResumeLimit;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create work component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateWork::class)
        ->assertSuccessful();
});

it('sets default values correctly', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateWork::class)
        ->assertSet('works.name', '');
});

it('creates a new work record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateWork::class)
        ->set('works.name', 'New Corp')
        ->set('works.position', 'Developer')
        ->set('works.starts_at', '2020-01')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('works', [
        'user_id' => $this->user->id,
        'name' => 'New Corp',
        'position' => 'Developer',
    ]);
});

it('validates required fields', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateWork::class)
        ->set('works.name', '')
        ->call('createForm')
        ->assertHasErrors(['name']);
});

it('work records have a limit', function () {
    $this->actingAs($this->user);
    Work::factory()->count(ResumeLimit::WORK)->create(['user_id' => $this->user->id]);

    Livewire::test(CreateWork::class)
        ->set('works.name', 'Extra Corp')
        ->set('works.position', 'Developer')
        ->set('works.starts_at', '2020-01')
        ->call('createForm');

    $this->assertDatabaseCount('works', ResumeLimit::WORK);
});
