<?php

namespace Ads\Core\Traits;

use Ads\Core\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRole
{
    /**
     * A user may have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign the given role to the user.
     *
     * @param array $roles
     * @return array
     */
    public function assignRoles(array $roles): array
    {
        return $this->roles()->sync(
            Role::whereIn('name', $roles)->get()
        );
    }

    /**
     * Determine if the user has the given role.
     *
     * @param mixed $role
     * @return boolean
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }
}
