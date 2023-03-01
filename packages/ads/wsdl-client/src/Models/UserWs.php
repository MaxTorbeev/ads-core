<?php

namespace Ads\WsdClient\Models;

use Illuminate\Database\Eloquent\Model;

class UserWs extends Model
{
    protected $table = 'users_ws';

    protected $fillable = [
        'url',
        'login',
        'password'
    ];
}
