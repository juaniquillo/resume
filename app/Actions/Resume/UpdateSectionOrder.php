<?php

namespace App\Actions\Resume;

use App\Enums\ResumeSection;
use App\Models\SectionOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateSectionOrder
{
    public function handle(User $user, string $section, int $newPosition): void
    {
        DB::transaction(function () use ($user, $section, $newPosition) {
            $this->ensureAllSectionsExist($user);

            $orders = $user->sectionOrders()->orderBy('sort_order')->get();
            $currentSections = $orders->pluck('section')->toArray();

            // Remove the section from its current position
            $key = array_search($section, $currentSections);
            if ($key !== false) {
                unset($currentSections[$key]);
            }

            // Re-index and insert at new position
            $currentSections = array_values($currentSections);
            array_splice($currentSections, $newPosition, 0, $section);

            // Update database
            foreach ($currentSections as $index => $sec) {
                SectionOrder::updateOrCreate(
                    ['user_id' => $user->id, 'section' => $sec],
                    ['sort_order' => $index]
                );
            }
        });
    }

    public function ensureAllSectionsExist(User $user): void
    {
        $existing = $user->sectionOrders()->pluck('section')->toArray();
        $all = collect(ResumeSection::cases())->pluck('value')->toArray();
        $missing = array_diff($all, $existing);

        if (empty($missing)) {
            return;
        }

        $maxOrder = $user->sectionOrders()->max('sort_order') ?? -1;

        foreach ($missing as $index => $sec) {
            SectionOrder::create([
                'user_id' => $user->id,
                'section' => $sec,
                'sort_order' => $maxOrder + 1 + $index,
            ]);
        }
    }
}
