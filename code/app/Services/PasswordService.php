<?php

namespace App\Services;

class PasswordService
{
    public static function isPwnPassword($password)
    {
        $list = file_get_contents(('../data/PwnedPasswordsTop100k.json'));
        $list = json_decode($list, true);
        return in_array($password, $list);
    }

    public static function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
