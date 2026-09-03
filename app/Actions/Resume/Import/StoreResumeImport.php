<?php

namespace App\Actions\Resume\Import;

use App\Cruds\Schema\ResumeImport\Inputs\JsonFileFactory;
use App\Cruds\Schema\ResumeImport\Inputs\NameFactory;
use App\Enums\ProcessStatus;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use JustSteveKing\Resume\Factories\ResumeFactory;

class StoreResumeImport
{
    /**
     * @param  array{name: string, resume_file: UploadedFile}  $data
     */
    public function handle(User $user, array $data): ResumeImport
    {
        /** @var UploadedFile $file */
        $file = $data[JsonFileFactory::NAME];

        try {
            $json = file_get_contents($file->getRealPath());
            ResumeFactory::fromJson($json)->validate();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                JsonFileFactory::NAME => ['The provided file does not conform to the JSON Resume schema.'],
            ]);
        }

        $path = $file->store('imports/resumes');

        $nameField = NameFactory::NAME;

        /** @var ResumeImport $import */
        $import = $user->resumeImports()->create([
            $nameField => $data[$nameField],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'status' => ProcessStatus::PENDING,
        ]);

        return $import;
    }
}
