<?php

namespace App\Models;

use Core\Model;

class Appointment extends Model
{
    protected static $table = 'appointments';

    protected $fillable = [
        'user_id',
        'english_first_name',
        'english_last_name',
        'hkid',
        'appointment_date',
        'appointment_time',
        'venue'
    ];
}
