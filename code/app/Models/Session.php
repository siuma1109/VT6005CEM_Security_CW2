<?php

namespace App\Models;

use Core\Model;

class Session extends Model
{
    protected static $table = 'sessions';

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity'
    ];
}
