<?php

namespace App\Actions\Highlights;

use App\Models\Contracts\HighlightModel;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;

class CreateHighlight
{
    public function __construct(
        private User $user,
        private Model|HighlightModel $model,
        private array $data
    )
    {}

    public function handle(): void
    {
        if($this->model->getUserId() !== $this->user->id) {
            throw new AuthenticationException('You are not authorized to create a highlight for this user');
        }
        
        $this->model
            ->highlights()
            ->create($this->data);
    }
}
