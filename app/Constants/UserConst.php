<?php

namespace App\Constants;

class UserConst
{
    const SUPERADMIN = 1;

    const TOOLSMAN = 2;

    const USER = 3;

    public static function getAccessTypes()
    {
        return [
            self::SUPERADMIN => 'Super Admin',
            self::TOOLSMAN => 'Toolsman',
            self::USER => 'User',
        ];
    }
}
