<?php

namespace App\Actions\Highlights;

use App\Models\Contracts\HighlightModel;
use App\Models\Highlight;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;

class UpdateHighlight
{
    public function __construct(
        private User $user,
        private Highlight $highlight,
        private array $data
    )
    {}

    public function handle(): void
    {
        $highlight = $this->highlight;

        /** @var Model|HighlightModel $parent */
        $parent = $highlight->highlightable;

        if($parent->getUserId() !== $this->user->id) {
            throw new AuthenticationException('You are not authorized to update a highlight for this user');
        }
        
        $highlight->update($this->data);
    }
}
