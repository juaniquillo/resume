<?php

use App\Livewire\Resume\Works\EditWork;
use App\Models\User;
use App\Models\Work;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit work component', function () {
    $work = Work::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditWork::class, ['workId' => $work->id])
        ->assertSuccessful();
});

it('formats date fields for the UI', function () {
    $work = Work::factory()->create([
        'user_id' => $this->user->id,
        'starts_at' => '2020-01-01',
        'ends_at' => '2021-01-01',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditWork::class, ['workId' => $work->id])
        ->assertSet('works.starts_at', '2020-01')
        ->assertSet('works.ends_at', '2021-01');
});

it('updates an existing work record', function () {
    $work = Work::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Corp',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditWork::class, ['workId' => $work->id])
        ->set('works.name', 'New Corp')
        ->set('works.position', 'Senior Developer')
        ->set('works.starts_at', '2020-01-01')
        ->set('works.ends_at', '2021-01-01')
        ->set('works.summary', 'Updated summary')
        ->call('updateForm')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('works', [
        'id' => $work->id,
        'name' => 'New Corp',
        'position' => 'Senior Developer',
    ]);
});

it('validates update form', function () {
    $work = Work::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Corp',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditWork::class, ['workId' => $work->id])
        ->set('works.name', '')
        ->call('updateForm')
        ->assertHasErrors(['name']);
});
