<?php

use App\Livewire\Resume\Certificates\CreateCertificate;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create certificate component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateCertificate::class)
        ->assertSuccessful();
});

it('creates a new certificate record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateCertificate::class)
        ->set('certificates.name', 'Certified Laravel Developer')
        ->set('certificates.date', '2023-10-26')
        ->set('certificates.url', 'https://example.com/certificate')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('certificates', [
        'user_id' => $this->user->id,
        'name' => 'Certified Laravel Developer',
    ]);
});
