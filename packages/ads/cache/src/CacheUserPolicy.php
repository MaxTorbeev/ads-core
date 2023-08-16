<?php

namespace Ads\Cache;

use App\Models\User;

trait CacheUserPolicy
{
    public function cache_clear(User $user): bool
    {
        return $user->hasPermission('cache_clear');
    }
}
