<?php

namespace Ads\Core\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function user_create(User $user): bool
    {
        return $user->hasPermission('user_create');
    }

    public function user_show(User $user): bool
    {
        return $user->hasPermission('user_show');
    }

    public function user_delete(User $user): bool
    {
        return $user->hasPermission('user_delete');
    }

    public function cache_clear(User $user): bool
    {
        return $user->hasPermission('cache_clear');
    }
}
