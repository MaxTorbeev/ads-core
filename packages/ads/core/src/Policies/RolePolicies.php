<?php

namespace Ads\Core\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use Ads\Core\Models\Permission;

class RolePolicies
{
    use HandlesAuthorization;

    public static function define(): void
    {
        foreach (self::getPermissions() as $permission) {
            Gate::define($permission->name, fn($user) => $user->hasRole($permission->roles));
        }
    }

    protected static function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}
