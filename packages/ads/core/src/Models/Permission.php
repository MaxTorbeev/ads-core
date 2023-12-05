<?php

namespace Ads\Core\Models;

use Ads\Core\Exceptions\PermissionDoesNotExistException;
use Ads\Core\Traits\HasRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, HasRole;

    /**
     * Find a permission by its name.
     *
     * @param $name
     * @return Permission
     * @throws PermissionDoesNotExistException
     */
    public static function findByName($name): Permission
    {
        $permission = static::where('name', $name)->first();

        if (!$permission) {
            throw new PermissionDoesNotExistException();
        }

        return $permission;
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
