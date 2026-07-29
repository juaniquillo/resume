<?php

use App\Livewire\Resume\Publications\EditPublication;
use App\Models\Publication;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit publication component', function () {
    $publication = Publication::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditPublication::class, ['publicationId' => $publication->id])
        ->assertSuccessful();
});

it('updates an existing publication record successfully', function () {
    $publication = Publication::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Title',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditPublication::class, ['publicationId' => $publication->id])
        ->set('publications.name', 'New Publication Title')
        ->set('publications.date', '2024-01-15')
        ->set('publications.issuer', 'IEEE')
        ->set('publications.url', 'https://new-url.com/pub')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('publications', [
        'id' => $publication->id,
        'name' => 'New Publication Title',
    ]);
});
