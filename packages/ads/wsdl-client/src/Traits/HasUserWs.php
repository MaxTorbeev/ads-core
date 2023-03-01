<?php

namespace Ads\WsdClient\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasUserWs
{
    public function userWs(): HasOne
    {
        return $this->hasOne(self::class);
    }
}
