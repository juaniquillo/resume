<?php

namespace App\Actions\Resume\Basics;

use App\Models\Basic;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateBasics
{
    public function __construct(
        private array $data,
        private User $user,
        private ?UploadedFile $image = null
    ) {}

    public function handle(): Basic
    {
        $basics = Basic::where('user_id', $this->user->id)->first();

        if ($this->image) {
            if ($basics?->image) {
                Storage::delete($basics->image);
            }

            $this->data['image'] = Storage::putFile('basics', $this->image);
        } else {
            // If no new image is provided, we don't want to overwrite the existing one
            // unless we specifically want to remove it.
            // The controller logic was: unset($validated['image']);
            unset($this->data['image']);
        }

        return Basic::updateOrCreate(
            ['user_id' => $this->user->id],
            $this->data
        );
    }
}
