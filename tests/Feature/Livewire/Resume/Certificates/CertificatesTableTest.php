<?php

use App\Livewire\Resume\Certificates\CertificatesTable;
use App\Models\Certificate;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the certificates table component', function () {
    $this->actingAs($this->user);

    Livewire::test(CertificatesTable::class)
        ->assertSuccessful();
});

it('renders certificates in the table when records exist', function () {
    Certificate::factory()->count(2)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(CertificatesTable::class)
        ->assertSuccessful();
});
