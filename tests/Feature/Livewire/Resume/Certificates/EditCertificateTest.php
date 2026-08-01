<?php

use App\Livewire\Resume\Certificates\EditCertificate;
use App\Models\Certificate;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit certificate component', function () {
    $certificate = Certificate::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditCertificate::class, ['certificateId' => $certificate->id])
        ->assertSuccessful();
});

it('updates an existing certificate record successfully', function () {
    $certificate = Certificate::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Name',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditCertificate::class, ['certificateId' => $certificate->id])
        ->set('certificates.name', 'New Certificate Name')
        ->set('certificates.date', '2024-01-15')
        ->set('certificates.url', 'https://new-url.com/cert')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('certificates', [
        'id' => $certificate->id,
        'name' => 'New Certificate Name',
    ]);
});
