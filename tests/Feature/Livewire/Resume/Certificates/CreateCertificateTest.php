<?php

use App\Livewire\Resume\Certificates\CreateCertificate;
use App\Models\Certificate;
use App\Models\User;
use App\Support\ResumeLimit;
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

it('certificates records have a limit', function () {
    $this->actingAs($this->user);
    Certificate::factory()->count(ResumeLimit::CERTIFICATES)->create(['user_id' => $this->user->id]);

    Livewire::test(CreateCertificate::class)
        ->set('certificates.name', 'Extra Certificate')
        ->call('createForm');

    $this->assertDatabaseCount('certificates', ResumeLimit::CERTIFICATES);
});
