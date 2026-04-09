<?php

namespace App\Models;

use Database\Factories\VolunteerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    /** @use HasFactory<VolunteerFactory> */
    use HasFactory;
}
