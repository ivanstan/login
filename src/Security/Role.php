<?php

namespace App\Security;

use MyCLabs\Enum\Enum;

class Role extends Enum
{
    public const USER = 'user';
    public const ADMIN = 'admin';
}