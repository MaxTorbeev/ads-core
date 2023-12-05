<?php

namespace Ads\Core\Traits;

use Ads\Core\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;


trait HasRole
{
    /**
     * A user may have multiple roles.
     */
    public function defaultRoles(): BelongsToMany
    {
        // Если пользователь имеет родителя,
        // то роль берется от главного родителя
        if ($this->parent_id) {
            return $this->root()->defaultRoles();
        }

        return $this->belongsToMany(Role::class)->select(['id', 'label', 'name']);
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

        $roles = $this->defaultRoles()->with('permissions')->get();

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
     * Getting roles from parent.
     *
     * @return array
     */
    public function rolesFromParent(): array
    {
        /** @var User $parent */
        $parent = $this;

        // Если у пользователя нет родителя, то вернуть пустой массив
        if ($parent->parent_id === null) {
            return [];
        }

        while ($parent->parent && count($parent->parent->roles)) {
            $parent = $parent->parent;
        }

        return $parent->roles->toArray() ?? [];
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

    public function roles(): Attribute
    {
        $roles = $this->defaultRoles;

        if (!count($roles)) {
            $roles = $this->rolesFromParent();
        }

        return Attribute::make(
            get: fn() => $roles
        );
    }

    /**
     * Deleting role.
     *
     * @param int|string|Role $role
     * @return int
     */
    public function removeRole(int|string|Role $role): int
    {
        if ($role instanceof Role) {
            return $this->defaultRoles()->detach($role->id);
        }

        if ($ids = Role::query()->byCredentials([$role])->get()->pluck('id')) {
            return $this->defaultRoles()->detach($ids);
        }

        return false;
    }

    /**
     * Перезапись Ролей
     *
     * @param array $roles - Массив может содержать в себе id, name и инстансы класса Role
     */
    public function syncRoles(array $roles): void
    {
        $models = array_filter($roles, fn ($item) => $item instanceof Role);

        $roles = Role::query()->byCredentials($roles)->get() ?? collect();

        if (!empty($models)) {
            $roles = $roles->push($models);
        }

        if ($roles->count()) {
            $this->defaultRoles()->sync($roles);
        }
    }
}
