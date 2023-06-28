<?php

namespace Ads\WsdlClient\Traits;

use Ads\WsdlClient\Models\UserWs;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasUserWs
{
    public function userWs(): HasOne
    {
        return $this->hasOne(UserWs::class, 'id', 'user_ws_id');
    }
}
