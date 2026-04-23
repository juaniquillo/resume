<?php

namespace App\Models;

use Database\Factories\PublicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class publication extends Model
{
    /** @use HasFactory<PublicationFactory> */
    use HasFactory;
}
