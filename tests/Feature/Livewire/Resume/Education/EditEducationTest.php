<?php

use App\Enums\EducationLevel;
use App\Livewire\Resume\Education\EditEducation;
use App\Models\Education;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit education component', function () {
    $education = Education::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditEducation::class, ['educationId' => $education->id])
        ->assertSuccessful();
});

it('formats date fields for the UI', function () {
    $education = Education::factory()->create([
        'user_id' => $this->user->id,
        'starts_at' => '2016-01-01',
        'ends_at' => '2020-01-01',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditEducation::class, ['educationId' => $education->id])
        ->assertSet('education.starts_at', '2016-01')
        ->assertSet('education.ends_at', '2020-01');
});

it('updates an existing education record', function () {
    $education = Education::factory()->create([
        'user_id' => $this->user->id,
        'institution' => 'Old University',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditEducation::class, ['educationId' => $education->id])
        ->set('education.institution', 'New University')
        ->set('education.area', 'AI')
        ->set('education.study_type', EducationLevel::MASTER_DEGREE->value)
        ->set('education.starts_at', '2016-01-01')
        ->set('education.ends_at', '2020-01-01')
        ->call('updateForm')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('education', [
        'id' => $education->id,
        'institution' => 'New University',
        'study_type' => EducationLevel::MASTER_DEGREE->value,
    ]);
});
