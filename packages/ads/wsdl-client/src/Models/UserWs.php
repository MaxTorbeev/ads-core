<?php

namespace Ads\WsdlClient\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserWs extends Model
{
    protected $table = 'users_ws';

    protected $fillable = [
        'url',
        'login',
        'password'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(config('auth.providers.users.model'));
    }
}
