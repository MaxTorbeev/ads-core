<?php

namespace Ads\Cache\Traits;

use App\Models\User;

trait HasCacheUserPolicy
{
    public function cache_clear(User $user): bool
    {
        return $user->hasPermission('cache_clear');
    }
}
