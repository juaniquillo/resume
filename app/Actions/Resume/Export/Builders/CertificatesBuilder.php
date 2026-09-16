<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Certificates\CertificatesCrud;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CertificatesBuilder
{
    public function handle(User $user): ?array
    {
        $certificates = $user->resumeCertificates();
        if ($certificates->isEmpty()) {
            return null;
        }

        return $certificates->map(function (Model $cert) {
            return CertificatesCrud::build()->make()->execute(new ModelToExportAction($cert))->toArray();
        })->toArray();
    }
}
