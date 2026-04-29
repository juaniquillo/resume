<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Basics\UpdateProfile;
use App\Cruds\Squema\Profiles\ProfilesCrud;
use App\Http\Requests\ProfilesFormRequest;
use App\Models\Basic;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BasicsProfileController extends Controller
{
    /**
     * @return View|string
     */
    public function index(Request $request)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->basics()->first();

        $form = null;
        $table = null;
        $profiles = null;

        if ($basics) {
            $profiles = $basics->profiles()->paginate(10);

            $crud = ProfilesCrud::build(
                values: $values,
                errors: $errors,
            );

            $crud->setFormAction(route('dashboard.basics.profiles.store'));

            $form = $crud->formWithInputsSpanFull();
            $table = $crud->makeTable($profiles);
        }

        return view('dashboard.basics.profiles.index')
            ->with('basics', $basics)
            ->with('form', $form)
            ->with('table', $table)
            ->with('paginator', $profiles);
    }

    public function store(ProfilesFormRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->basics()->first();

        if (! $basics) {
            return back()
                ->with('custom_error', __('basics.errors.basics_not_found'));
        }

        $basics->profiles()->create($validated);

        return back()
            ->with('success', 'Profile created successfully.');
    }

    /**
     * @return View|string
     */
    public function edit(Request $request, int $id)
    {
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->basics()->first();
        $form = null;

        if ($basics) {
            $profile = $basics->profiles()->findOrFail($id);
            $crud = ProfilesCrud::build(
                values: $values ?: $profile->toArray(),
                errors: $errors,
                model: $profile,
            );

            $crud->setFormAction(route('dashboard.basics.profiles.update', $id));
            $form = $crud->formWithInputsSpanFull();
        }

        return view('dashboard.basics.profiles.edit')
            ->with('form', $form);
    }

    public function update(ProfilesFormRequest $request, int $id): RedirectResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic $basics */
        $basics = $user->basics()->firstOrFail();

        /** @var Profile $profile */
        $profile = $basics->profiles()->findOrFail($id);

        (new UpdateProfile($request->validated(), $profile))->handle();

        return back()
            ->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic $basics */
        $basics = $user->basics()->firstOrFail();

        /** @var Profile $profile */
        $profile = $basics->profiles()->findOrFail($id);

        $profile->delete();

        return back()
            ->with('success', 'Profile deleted successfully.');
    }
}
