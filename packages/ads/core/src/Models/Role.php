<?php

namespace Ads\Core\Models;

use Ads\Core\Traits\HasPermission;
use Ads\Core\Traits\HasUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Role extends Model
{
    use HasFactory, HasUser, HasPermission;

    protected $hidden = ['pivot'];

    /**
     * @use $role->permissions()
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->select('label', 'name');
    }

    /**
     * Sync roles on permission.
     *
     * @param array $permission
     * @return Model
     */
    public function syncPermissions(array $permission): Model
    {
        $models = array_filter($permission, fn ($item) => $item instanceof Permission);

        $permissions = Permission::query()->byCredentials($permission)->get() ?? collect();

        if (!empty($models)) {
            $permissions = $permissions->push($models);
        }

        if ($permissions->count()) {
            $this->permissions()->sync($permissions);
        }

        return $this;
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

    public function scopeByCredentials(Builder $query, array $roles): Builder
    {
        $ids = array_filter($roles, fn ($item) => is_numeric($item));
        $names = array_filter($roles, fn ($item) => is_string($item));

        return $query->where(function (Builder $builder) use ($ids, $names) {
            if (!empty($ids)) {
                $builder = $builder->orWhereIn('id', $ids);
            }

            if (!empty($names)) {
                $builder = $builder->orWhereIn('name', $names);
            }

            return $builder;
        });
    }
}
