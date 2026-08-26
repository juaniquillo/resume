<?php

use App\Enums\EducationLevel;
use App\Livewire\Resume\Education\CreateEducation;
use App\Models\Education;
use App\Models\User;
use App\Support\ResumeLimit;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create education component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateEducation::class)
        ->assertSuccessful();
});

it('creates a new education record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateEducation::class)
        ->set('education.institution', 'University of Life')
        ->set('education.area', 'Software Engineering')
        ->set('education.study_type', EducationLevel::BACHELOR_DEGREE->value)
        ->set('education.score', '4.0')
        ->set('education.starts_at', '2016-01-01')
        ->set('education.ends_at', '2020-01-01')
        ->set('education.url', 'https://example.edu')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('education', [
        'user_id' => $this->user->id,
        'institution' => 'University of Life',
        'area' => 'Software Engineering',
        'study_type' => EducationLevel::BACHELOR_DEGREE->value,
    ]);
});

it('education records have a limit', function () {
    $this->actingAs($this->user);
    Education::factory()->count(ResumeLimit::EDUCATION)->create(['user_id' => $this->user->id]);

    Livewire::test(CreateEducation::class)
        ->set('education.institution', 'Extra University')
        ->set('education.area', 'CS')
        ->set('education.study_type', EducationLevel::BACHELOR_DEGREE->value)
        ->call('createForm');

    $this->assertDatabaseCount('education', ResumeLimit::EDUCATION);
});
