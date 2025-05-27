<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected static $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
