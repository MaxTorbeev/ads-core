<?php

namespace Ads\Core\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function cache_clear(User $user): bool
    {
        return $user->hasPermission('cache_clear');
    }
}
