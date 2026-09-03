<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Resume\Basics\StoreProfile;
use App\Actions\Resume\Basics\UpdateBasics;
use App\Actions\Resume\Basics\UpdateLocation;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Basics\BasicsCrud;
use App\Cruds\Schema\Locations\LocationsCrud;
use App\Cruds\Schema\Profiles\ProfilesCrud;
use App\Models\Basic;
use App\Models\User;
use App\Services\ResumeImport\Concerns\HasImportValidation;
use Illuminate\Http\UploadedFile;

class BasicsProcessor
{
    use HasImportValidation;

    public function process(User $user, array $data): void
    {
        if (! isset($data['basics'])) {
            return;
        }

        $basicsData = $data['basics'];
        $crud = BasicsCrud::build();
        $inputs = $crud->make();

        /**
         * Check if image exists. If it does, fetch it and convert to UploadedFile
         * to allow strict image validation (mimes, size, etc.)
         */
        if (isset($basicsData['image']) && is_string($basicsData['image']) && ! empty($basicsData['image'])) {
            $image = $basicsData['image'];
            $contents = null;
            $extension = 'tmp';

            if (str_starts_with($image, 'data:')) {
                // Handle Data URI
                if (preg_match('/^data:image\/(\w+);base64,/', $image, $matches)) {
                    $extension = $matches[1];
                    $imageContent = substr($image, strpos($image, ',') + 1);
                    $contents = base64_decode($imageContent);
                }
            } else {
                // Handle URL
                $contents = @file_get_contents($image);
                $pathInfo = pathinfo(parse_url($image, PHP_URL_PATH) ?? '');
                $extension = $pathInfo['extension'] ?? 'tmp';
            }

            if ($contents) {
                $tempPath = tempnam(sys_get_temp_dir(), 'resume_import_');
                file_put_contents($tempPath, $contents);

                $basicsData['image'] = new UploadedFile(
                    $tempPath,
                    'avatar.'.$extension,
                    mime_content_type($tempPath),
                    null,
                    true
                );
            }
        }

        $mappedBasics = $inputs->execute(new NameValueAction($basicsData))
            ->toArray();

        $rules = $inputs->execute(new LaravelValidationRulesAction)->toArray();
        $validated = $this->validate($mappedBasics, $rules);

        $imageFile = $validated['image'] ?? null;
        if (! $imageFile instanceof UploadedFile) {
            $imageFile = null;
        }

        (new UpdateBasics($validated, $user, $imageFile))->handle();

        /** @var Basic|null $basics */
        $basics = $user->refresh()->resumeBasics();

        if ($basics) {
            if (isset($basicsData['location'])) {
                $locationCrud = LocationsCrud::build();
                $locationInputs = $locationCrud->make();

                $mappedLocation = $locationInputs->execute(new NameValueAction($basicsData['location']))
                    ->toArray();

                $locationRules = $locationInputs->execute(new LaravelValidationRulesAction)->toArray();
                $validatedLocation = $this->validate($mappedLocation, $locationRules);

                (new UpdateLocation($validatedLocation, $basics))->handle();
            }

            if (isset($basicsData['profiles'])) {
                $profileCrud = ProfilesCrud::build();
                $profileInputs = $profileCrud->make();
                $profileRules = $profileInputs->execute(new LaravelValidationRulesAction)->toArray();

                foreach ($basicsData['profiles'] as $profile) {
                    $mappedProfile = $profileInputs->execute(new NameValueAction($profile))
                        ->toArray();

                    $validatedProfile = $this->validate($mappedProfile, $profileRules);

                    (new StoreProfile($validatedProfile, $basics))->handle();
                }
            }
        }
    }
}
