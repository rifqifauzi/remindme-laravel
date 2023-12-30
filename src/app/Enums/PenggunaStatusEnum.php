<?php

namespace App\Enums;

enum PenggunaStatusEnum: string
{
    case FIRST_LOGIN = 'first_login';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

}
