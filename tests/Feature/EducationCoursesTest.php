<?php

use App\Livewire\Resume\Education\Courses\CoursesTable;
use App\Livewire\Resume\Education\Courses\CreateCourse;
use App\Livewire\Resume\Education\Courses\DeleteCourse;
use App\Livewire\Resume\Education\Courses\EditCourse;
use App\Models\Education;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->education = Education::factory()->create([
        'user_id' => $this->user->id,
    ]);
});

it('redirects guests to login from education courses index', function () {
    $this->get(route('dashboard.education.courses', $this->education->id))
        ->assertRedirect(route('login'));
});

it('renders the education courses index page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.education.courses', $this->education->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.education.courses.index')
        ->assertViewHas('education');
});

it('renders the create course component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateCourse::class, ['educationId' => $this->education->id])
        ->assertSuccessful();
});

it('creates a new course successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateCourse::class, ['educationId' => $this->education->id])
        ->set('courses.course', 'Advanced Laravel Development')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('courses', [
        'courseable_id' => $this->education->id,
        'courseable_type' => Education::class,
        'course' => 'Advanced Laravel Development',
    ]);
});

it('renders the edit course component', function () {
    $course = $this->education->courses()->create([
        'course' => 'Old course',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditCourse::class, ['educationId' => $this->education->id, 'courseId' => $course->id])
        ->assertSuccessful();
});

it('updates an existing course successfully', function () {
    $course = $this->education->courses()->create([
        'course' => 'Old course',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditCourse::class, ['educationId' => $this->education->id, 'courseId' => $course->id])
        ->set('courses.course', 'New course name')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'course' => 'New course name',
    ]);
});

it('deletes a course successfully', function () {
    $course = $this->education->courses()->create([
        'course' => 'To be deleted',
    ]);

    $this->actingAs($this->user);

    Livewire::test(DeleteCourse::class, ['educationId' => $this->education->id, 'courseId' => $course->id])
        ->call('deleteCourse')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('courses', [
        'id' => $course->id,
    ]);
});

it('displays courses records in the table', function () {
    $this->education->courses()->create([
        'course' => 'Sample Course',
    ]);

    $this->actingAs($this->user);

    Livewire::test(CoursesTable::class, ['educationId' => $this->education->id])
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});
