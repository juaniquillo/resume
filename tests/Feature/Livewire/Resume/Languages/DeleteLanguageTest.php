<?php

use App\Livewire\Resume\Languages\DeleteLanguage;
use App\Models\Language;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes an existing language record successfully', function () {
    $language = Language::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteLanguage::class, ['languageId' => $language->id])
        ->call('deleteLanguage')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('languages', [
        'id' => $language->id,
    ]);
});
