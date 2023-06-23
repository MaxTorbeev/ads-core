<?php

namespace Ads\Core\Models;

use Ads\Core\Traits\HasPermission;
use Ads\Core\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory, HasUser, HasPermission;

    /**
     * @use $role->permissions()
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Sync roles on permission.
     *
     * @param Permission $permission
     * @return Model
     */
    public function givePermissionTo(Permission $permission): Model
    {
        return $this->permissions()->save($permission);
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     * @return bool
     */
    public function hasPermissionTo(Permission|string $permission): bool
    {
        if (is_string($permission)) {
            $permission = app(Permission::class)->findByName($permission);
        }

        return $this->permissions->contains('id', $permission->id);
    }
}
