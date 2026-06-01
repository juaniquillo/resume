<?php

namespace App\Livewire\Options;

use App\Actions\Resume\UpdateSectionOrder;
use App\Enums\ResumeSection;
use App\Models\SectionOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SectionOrdering extends Component
{
    public function handleSort(string $section, int $position): void
    {
        /** @var User $user */
        $user = Auth::user();

        (new UpdateSectionOrder)->handle($user, $section, $position);

        session()->flash('success', __('Section order updated successfully.'));
    }

    #[Computed]
    public function sections(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        (new UpdateSectionOrder)->ensureAllSectionsExist($user);

        $sections = $user->sectionOrders()->orderBy('sort_order')->get()
            ->map(function (Model $order) {
                /** @var SectionOrder $order */
                return (object) [
                    'value' => $order->section,
                    'label' => ResumeSection::tryFrom($order->section)?->label() ?? $order->section,
                ];
            });

        $this->dispatch('resume-order-updated');

        return $sections;
    }

    public function render()
    {
        return view('livewire.options.section-ordering');
    }
}
