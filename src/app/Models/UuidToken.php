<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UuidToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token_id',
        'access_token',
        'refresh_token',
    ];
}
