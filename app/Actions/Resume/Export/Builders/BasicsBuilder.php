<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Basics\BasicsCrud;
use App\Cruds\Schema\Basics\Inputs\EmailFactory;
use App\Cruds\Schema\Basics\Inputs\NameFactory;
use App\Cruds\Schema\Locations\LocationsCrud;
use App\Cruds\Schema\Profiles\ProfilesCrud;
use App\Models\Basic;
use App\Models\GeneralOption;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;

class BasicsBuilder
{
    public function handle(User $user, ?GeneralOption $generalOptions): array
    {
        /** @var Basic|null $basics */
        $basics = $user->resumeBasics();

        if (! $basics || (! $basics->{NameFactory::NAME} || ! $basics->{EmailFactory::NAME})) {
            throw new Exception(BasicsCrud::MISSING_BASICS_ERROR);
        }

        $basicsArray = BasicsCrud::build()->make()->execute(new ModelToExportAction($basics))->toArray();
        /** 
         * @TODO Instead of hardcoding every unset we could
         * use GeneralOptionsCrud::optionsInputsArray() 
         * to loop through the input factory names
         * in case we add one later
        */
        if ($generalOptions) {
            if ($generalOptions->getAttribute('hide_email') && isset($basicsArray['email'])) {
                unset($basicsArray['email']);
            }
            if ($generalOptions->getAttribute('hide_phone') && isset($basicsArray['phone'])) {
                unset($basicsArray['phone']);
            }
            if ($generalOptions->getAttribute('hide_image') && isset($basicsArray['image'])) {
                unset($basicsArray['image']);
            }
        }

        if ($basics->location) {
            $locationArray = LocationsCrud::build()->make()->execute(new ModelToExportAction($basics->location))->toArray();
            if ($generalOptions && $generalOptions->getAttribute('hide_address')) {
                $locationArray = [];
            }
            if (! empty($locationArray)) {
                $basicsArray['location'] = $locationArray;
            }
        }

        if ($basics->profiles->isNotEmpty()) {
            $profilesCrud = ProfilesCrud::build()->make();
            $basicsArray['profiles'] = $basics->profiles->map(function (Model $profile) use ($profilesCrud) {
                return $profilesCrud->execute(
                    new ModelToExportAction($profile)
                )->toArray();
            })->toArray();
        }

        return $basicsArray;
    }
}
