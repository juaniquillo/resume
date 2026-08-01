<?php

use App\Livewire\Resume\Certificates\DeleteCertificate;
use App\Models\Certificate;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an existing certificate record successfully', function () {
    $certificate = Certificate::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteCertificate::class, ['certificateId' => $certificate->id])
        ->call('deleteCertificate')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('certificates', [
        'id' => $certificate->id,
    ]);
});
