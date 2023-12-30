<?php

namespace App\Enums;

enum ResponseMessageEnum: string
{
    case SUCCESS = 'success';
    case VALIDATION_ERROR = 'validation error';
    case UNAUTHORIZED = 'authorization error';
    case UNAUTHENTICATED = 'authentication error';
    case NOT_FOUND = 'not found';
    case ROLE_MISMATCH = 'Pengguna role not valid';
    case STATUS_MISMATCH = 'Pengguna status not valid';
    case TYPE_ERROR = 'TypeError was raised';
    case UNPROCESSABLE_ENTITY = 'unprocessable entity';
    case TRUE = 'true';
    case FALSE = 'false';
}
