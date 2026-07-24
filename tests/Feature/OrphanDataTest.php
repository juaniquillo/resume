<?php

use App\Models\Basic;
use App\Models\Education;
use App\Models\Highlight;
use App\Models\Project;
use App\Models\ResumeExport;
use App\Models\ResumeImport;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Work;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('deleting a user cleans up all related data and files', function () {
    Storage::fake();
    $disk = Storage::disk();

    $user = User::factory()->create();

    // Create Basic with image
    $imagePath = $disk->putFile('basics', UploadedFile::fake()->image('profile.jpg'));
    $basic = Basic::factory()->create([
        'user_id' => $user->id,
        'image' => $imagePath,
    ]);

    // Create ResumeExport with file
    $exportPath = 'exports/resumes/test-export.pdf';
    $disk->put($exportPath, 'pdf content');
    $export = ResumeExport::factory()->create([
        'user_id' => $user->id,
        'file_path' => $exportPath,
    ]);

    // Create ResumeImport with file
    $importPath = 'imports/resumes/test-import.json';
    $disk->put($importPath, 'json content');
    $import = ResumeImport::factory()->create([
        'user_id' => $user->id,
        'file_path' => $importPath,
    ]);

    // Verify they exist
    $disk->assertExists($imagePath);
    $disk->assertExists($exportPath);
    $disk->assertExists($importPath);

    // Create other sections
    Work::factory()->for($user)->create();
    Education::factory()->for($user)->create();

    // Delete user
    $user->delete();

    // Assert database records are gone
    expect(User::find($user->id))->toBeNull();
    expect(Basic::where('user_id', $user->id)->exists())->toBeFalse();
    expect(ResumeExport::where('user_id', $user->id)->exists())->toBeFalse();
    expect(ResumeImport::where('user_id', $user->id)->exists())->toBeFalse();
    expect($user->works()->exists())->toBeFalse();
    expect($user->education()->exists())->toBeFalse();

    // Assert files are gone
    $disk->assertMissing($imagePath);
    $disk->assertMissing($exportPath);
    $disk->assertMissing($importPath);
});

test('deleting a user cleans up polymorphic highlights', function () {
    $user = User::factory()->create();

    $work = Work::factory()->for($user)->create();
    $work->highlights()->create(['highlight' => 'Work highlight']);

    $volunteer = Volunteer::factory()->for($user)->create();
    $volunteer->highlights()->create(['highlight' => 'Volunteer highlight']);

    $project = Project::factory()->for($user)->create();
    $project->highlights()->create(['highlight' => 'Project highlight']);

    expect(Highlight::count())->toBe(3);

    $user->delete();

    expect(Highlight::count())->toBe(0);
});
