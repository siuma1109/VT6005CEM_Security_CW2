<?php

namespace App\Services;

class PasswordService
{
    public static function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
