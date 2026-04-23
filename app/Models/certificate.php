<?php

namespace App\Models;

use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class certificate extends Model
{
    /** @use HasFactory<CertificateFactory> */
    use HasFactory;
}
