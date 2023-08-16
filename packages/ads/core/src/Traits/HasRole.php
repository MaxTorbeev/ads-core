<?php

namespace Ads\Core\Traits;

use Ads\Core\Models\Permission;
use Ads\Core\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasRole
{
    /**
     * A user may have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->select(['id', 'label', 'name']);
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

    /**
     * User has permission.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions);
    }

    public function collectPermissions(): array
    {
        $result = collect();

        $roles = $this->roles()->with('permissions')->get();

        return $roles
            ->map(fn($item) => $item->permissions->map(fn($p) => $p->name))
            ->each(fn($item) => $result->push($item->toArray()))
            ->flatten()
            ->unique()
            ->toArray();
    }

    /**
     * Get permissions from parent user.
     *
     * @return array
     */
    public function permissionsFromParent(): array
    {
        /** @var User $parent */
        $parent = $this;

        while ($parent->parent && count($parent->parent->permissions)) {
            $parent = $parent->parent;
        }

        return $parent->permissions ?? [];
    }

    /**
     * Get all permissions for user
     *
     * @return Attribute<array>
     */
    public function permissions(): Attribute
    {
        $permissions = $this->collectPermissions();

        if (!count($permissions)) {
            $permissions = $this->permissionsFromParent();
        }

        return Attribute::make(
            get: fn() => $permissions
        );
    }
}
